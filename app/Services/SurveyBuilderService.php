<?php

namespace App\Services;

use App\Models\InputType;
use App\Models\OptionChoice;
use App\Models\OptionGroup;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\SurveySection;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveyBuilderService
{
    // -------------------------------------------------------------------------
    // Queries / read helpers
    // -------------------------------------------------------------------------

    /**
     * Return the wards the given user is allowed to see.
     */
    public function getAccessibleWards(User $user): Collection
    {
        return $user->isSuperAdmin()
            ? Ward::select('id', 'ward_no')->get()
            : Ward::where('id', $user->ward_id)->select('id', 'ward_no')->get();
    }

    /**
     * Return the ward ID that should actually be used based on the user's role.
     * SuperAdmins use whatever was passed in; Ward Admins are locked to their own ward.
     */
    public function resolveWardId(User $user, ?int $requestedWardId): ?int
    {
        return $user->isSuperAdmin() ? $requestedWardId : $user->ward_id;
    }

    /**
     * Return sections for a ward, ordered by order_index.
     */
    public function getSectionsForWard(int $wardId): Collection
    {
        return SurveySection::where('ward_id', $wardId)
            ->select('id', 'title', 'order_index')
            ->orderBy('order_index')
            ->get();
    }

    /**
     * Load a section with all nested relations needed for the edit view.
     */
    public function getSectionForEdit(int $id): SurveySection
    {
        return SurveySection::with([
            'questions.inputType',
            'questions.optionGroup.optionChoices',
            'questions.questionOptions.optionChoice',
        ])->findOrFail($id);
    }

    /**
     * Transform a SurveySection (with its questions) into the flat JS-friendly
     * format expected by the front-end builder.
     *
     * @return array{sections: array, questions: array}
     */
    public function formatSectionForBuilder(SurveySection $section): array
    {
        $formattedSections = collect([
            [
                'id'          => 's' . $section->id,
                'title'       => $section->title,
                'description' => $section->description ?? '',
                'database_id' => $section->id,
            ],
        ]);

        $formattedQuestions = $section->questions
            ->sortBy('order_index')
            ->map(function (Question $question) use ($section) {
                $formatted = [
                    'id'               => 'q' . $question->id,
                    'sectionId'        => 's' . $section->id,
                    'type'             => $question->inputType?->input_type_name ?? 'unknown',
                    'label'            => $question->question_text,
                    'description'      => $question->question_subtext ?? '',
                    'required'         => $question->answer_required,
                    'database_id'      => $question->id,
                    'scale_from'       => $question->scale_from,
                    'scale_to'         => $question->scale_to,
                    'scale_label_low'  => $question->scale_label_low,
                    'scale_label_high' => $question->scale_label_high,
                ];

                if ($question->optionGroup?->optionChoices?->isNotEmpty()) {
                    $formatted['options'] = $question->optionGroup->optionChoices
                        ->map(fn (OptionChoice $choice) => [
                            'id'                => 'opt' . $choice->id,
                            'label'             => $choice->choice_text,
                            'value'             => strtolower(str_replace(' ', '_', $choice->choice_text)),
                            'input_type'        => $choice->custom_input_type ?? 'none',
                            'input_placeholder' => $choice->custom_input_placeholder ?? '',
                            'database_id'       => $choice->id,
                        ])
                        ->values()
                        ->toArray();
                }

                return $formatted;
            })
            ->values();

        return [
            'formattedSections'  => $formattedSections,
            'formattedQuestions' => $formattedQuestions,
        ];
    }

    // -------------------------------------------------------------------------
    // Write operations
    // -------------------------------------------------------------------------

    /**
     * Reorder sections. Skips sections that don't belong to the user's ward
     * (unless the user is a SuperAdmin).
     */
    public function reorderSections(User $user, array $orderItems): void
    {
        foreach ($orderItems as $item) {
            $section = SurveySection::findOrFail($item['id']);

            if (! $user->isSuperAdmin() && $section->ward_id != $user->ward_id) {
                continue;
            }

            $section->update(['order_index' => $item['order_index']]);
        }
    }

    /**
     * Persist a full survey (sections + questions) for one or many wards.
     *
     * @param  array  $wardIds      One or more ward IDs.
     * @param  array  $surveyData   Decoded payload from StoreSurveyRequest.
     * @return void
     *
     * @throws \Exception
     */
    public function createSurvey(array $wardIds, array $surveyData): void
    {
        DB::beginTransaction();

        try {
            foreach ($wardIds as $wardId) {
                $sectionMap = $this->persistSections($surveyData['sections'], $wardId);
                $this->persistQuestions($surveyData['questions'], $sectionMap, $wardId);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Survey creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Replace the sections + questions of an existing section with new data.
     *
     * @throws \Exception
     */
    public function updateSurvey(SurveySection $section, array $surveyData, int $wardId): void
    {
        DB::beginTransaction();

        try {
            $this->deleteQuestionsForSection($section);

            $sectionData = $surveyData['sections'][0];
            $section->update([
                'title'       => $sectionData['title'],
                'description' => $sectionData['description'] ?? null,
            ]);

            // Single section → use its DB id directly.
            $sectionMap = [$surveyData['sections'][0]['id'] => $section->id];
            $this->persistQuestions($surveyData['questions'], $sectionMap, $wardId);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Survey update failed: ' . $e->getMessage(), [
                'section_id' => $section->id,
                'trace'      => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Cascade-delete a section and all its questions / option groups / choices.
     *
     * @throws \Exception
     */
    public function deleteSection(SurveySection $section): void
    {
        DB::beginTransaction();

        try {
            $this->deleteQuestionsForSection($section);
            $section->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Section deletion failed: ' . $e->getMessage(), [
                'section_id' => $section->id,
            ]);
            throw $e;
        }
    }

    // -------------------------------------------------------------------------
    // Private helpers (single responsibility, reused by create & update)
    // -------------------------------------------------------------------------

    /**
     * Persist an array of section data rows for a given ward.
     *
     * @return array<string, int>  Map of frontend section id → DB id.
     */
    private function persistSections(array $sections, int $wardId): array
    {
        $sectionMap = [];

        foreach ($sections as $index => $sectionData) {
            $section = SurveySection::create([
                'title'       => $sectionData['title'],
                'description' => $sectionData['description'] ?? null,
                'ward_id'     => $wardId,
                'order_index' => $index + 1,
            ]);

            $sectionMap[$sectionData['id']] = $section->id;
        }

        return $sectionMap;
    }

    /**
     * Persist questions (and their option groups/choices/pivots) for a section map.
     *
     * @param  array<string, int>  $sectionMap  Frontend id → DB id.
     */
    private function persistQuestions(array $questions, array $sectionMap, int $wardId): void
    {
        foreach ($questions as $orderIndex => $questionData) {
            $inputType = InputType::firstOrCreate(
                ['input_type_name' => $questionData['type']]
            );

            $optionGroupId = $this->persistOptionGroup($questionData, $wardId);

            $question = Question::create([
                'survey_section_id'            => $sectionMap[$questionData['sectionId']],
                'question_text'                => $questionData['label'],
                'question_subtext'             => $questionData['description'] ?? null,
                'answer_required'              => $this->castRequired($questionData['required'] ?? false),
                'input_type_id'                => $inputType->id,
                'option_group_id'              => $optionGroupId,
                'allow_multiple_option_answers' => in_array($questionData['type'], ['checkbox'], true),
                'order_index'                  => $orderIndex + 1,
                'scale_from'                   => $questionData['scale_from'] ?? null,
                'scale_to'                     => $questionData['scale_to'] ?? null,
                'scale_label_low'              => $questionData['scale_label_low'] ?? null,
                'scale_label_high'             => $questionData['scale_label_high'] ?? null,
            ]);

            if ($optionGroupId) {
                $this->linkQuestionOptions($question, $optionGroupId);
            }
        }
    }

    /**
     * Create an OptionGroup + OptionChoices for a question if it has options.
     * Returns the new option_group_id, or null.
     */
    private function persistOptionGroup(array $questionData, int $wardId): ?int
    {
        if (empty($questionData['options'])) {
            return null;
        }

        $group = OptionGroup::create([
            'option_group_name' => $questionData['label'] . ' Options - Ward ' . $wardId,
        ]);

        foreach ($questionData['options'] as $optionData) {
            OptionChoice::create([
                'option_group_id'         => $group->id,
                'choice_text'             => $optionData['label'],
                'custom_input_type'       => $optionData['input_type'] ?? 'none',
                'custom_input_placeholder' => $optionData['input_placeholder'] ?? null,
            ]);
        }

        return $group->id;
    }

    /**
     * Populate the question_options pivot from the already-saved OptionChoices.
     */
    private function linkQuestionOptions(Question $question, int $optionGroupId): void
    {
        $choices = OptionChoice::where('option_group_id', $optionGroupId)->get();

        foreach ($choices as $choice) {
            QuestionOption::create([
                'question_id'      => $question->id,
                'option_choice_id' => $choice->id,
            ]);
        }
    }

    /**
     * Cascade-delete all questions (and their option data) for a section.
     */
    private function deleteQuestionsForSection(SurveySection $section): void
    {
        $questions = Question::where('survey_section_id', $section->id)->get();

        foreach ($questions as $question) {
            if ($question->option_group_id) {
                QuestionOption::where('question_id', $question->id)->delete();
                OptionChoice::where('option_group_id', $question->option_group_id)->delete();
                OptionGroup::where('id', $question->option_group_id)->delete();
            }

            $question->delete();
        }
    }

    /**
     * Normalise the various truthy values a "required" flag may arrive as.
     */
    private function castRequired(mixed $value): bool
    {
        return $value === true || $value == '1';
    }
}
