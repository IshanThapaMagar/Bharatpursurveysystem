<?php

namespace App\Services;

use App\Models\Ward;
use App\Models\SurveySection;
use App\Models\Question;
use App\Models\OptionChoice;
use App\Models\QuestionOption;
use App\Models\OptionGroup;
use App\Models\InputType;
use Illuminate\Support\Facades\DB;

class SurveyDuplicationService
{
    /**
     * @param int $sourceWardId The ward to copy surveys from
     * @param int $targetWardId The new ward to copy surveys to
     * @return bool
     */
    public function duplicateSurveysToNewWard($sourceWardId, $targetWardId)
    {
        try {
            $sourceSections = SurveySection::where('ward_id', $sourceWardId)
                ->ordered()
                ->with(['questions.optionGroup.optionChoices', 'questions.inputType', 'questions.questionOptions'])
                ->get();

            if ($sourceSections->isEmpty()) {
                return true; // No surveys to duplicate
            }

            DB::beginTransaction();

            foreach ($sourceSections as $sourceSection) {
                // Create new section for target ward
                $newSection = SurveySection::create([
                    'title' => $sourceSection->title,
                    'description' => $sourceSection->description,
                    'ward_id' => $targetWardId,
                    'order_index' => $sourceSection->order_index,
                ]);

                // Duplicate all questions in this section
                foreach ($sourceSection->questions as $sourceQuestion) {
                    $optionGroupId = null;

                    // Duplicate option group and choices if they exist
                    if ($sourceQuestion->option_group_id) {
                        $sourceOptionGroup = $sourceQuestion->optionGroup;
                        $newOptionGroup = OptionGroup::create([
                            'option_group_name' => $sourceOptionGroup->option_group_name
                        ]);
                        $optionGroupId = $newOptionGroup->id;

                        // Copy all option choices
                        foreach ($sourceOptionGroup->optionChoices as $sourceChoice) {
                            OptionChoice::create([
                                'option_group_id' => $newOptionGroup->id,
                                'choice_text' => $sourceChoice->choice_text,
                                'custom_input_type' => $sourceChoice->custom_input_type,
                                'custom_input_placeholder' => $sourceChoice->custom_input_placeholder,
                            ]);
                        }
                    }

                    // Create new question
                    $newQuestion = Question::create([
                        'survey_section_id' => $newSection->id,
                        'question_text' => $sourceQuestion->question_text,
                        'question_subtext' => $sourceQuestion->question_subtext,
                        'answer_required' => $sourceQuestion->answer_required,
                        'input_type_id' => $sourceQuestion->input_type_id,
                        'option_group_id' => $optionGroupId,
                        'allow_multiple_option_answers' => $sourceQuestion->allow_multiple_option_answers,
                        'order_index' => $sourceQuestion->order_index,
                        'scale_from' => $sourceQuestion->scale_from,
                        'scale_to' => $sourceQuestion->scale_to,
                        'scale_label_low' => $sourceQuestion->scale_label_low,
                        'scale_label_high' => $sourceQuestion->scale_label_high,
                    ]);

                    // Link question options
                    if ($optionGroupId) {
                        $newOptionChoices = OptionChoice::where('option_group_id', $optionGroupId)->get();
                        
                        foreach ($newOptionChoices as $optionChoice) {
                            QuestionOption::create([
                                'question_id' => $newQuestion->id,
                                'option_choice_id' => $optionChoice->id
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Survey duplication failed: ' . $e->getMessage(), [
                'source_ward_id' => $sourceWardId,
                'target_ward_id' => $targetWardId,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     *
     * @param int $newWardId
     * @return bool
     */
    public function assignExistingSurveysToNewWard($newWardId)
    {
        // Find the first ward that has surveys
        $wardWithSurveys = Ward::whereHas('surveySections')
            ->first();

        if (!$wardWithSurveys) {
            return true; // No surveys exist yet to duplicate
        }

        return $this->duplicateSurveysToNewWard($wardWithSurveys->id, $newWardId);
    }
}