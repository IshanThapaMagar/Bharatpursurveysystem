<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Only non-DataCollectors may create surveys.
     * Fine-grained ward ownership is enforced in SurveyBuilderService.
     */
    public function authorize(): bool
    {
        return ! $this->user()->isDataCollector();
    }

    /**
     * Decode the JSON survey_data payload and merge it back so the nested
     * rules below can reach it without manual json_decode() in the controller.
     */
    protected function prepareForValidation(): void
    {
        $decoded = json_decode($this->input('survey_data', '{}'), true);

        if (is_array($decoded)) {
            $this->merge([
                'sections'  => $decoded['sections']  ?? null,
                'questions' => $decoded['questions'] ?? null,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            // Top-level
            'ward_id'     => ['required'],
            'survey_data' => ['required', 'json'],

            // Decoded payload
            'sections'                              => ['required', 'array', 'min:1'],
            'sections.*.title'                      => ['required', 'string', 'max:255'],
            'sections.*.description'                => ['nullable', 'string'],
            'questions'                             => ['required', 'array', 'min:1'],
            'questions.*.label'                     => ['required', 'string'],
            'questions.*.type'                      => ['required', 'string'],
            'questions.*.sectionId'                 => ['required', 'string'],
            'questions.*.required'                  => ['nullable', 'boolean'],
            'questions.*.description'               => ['nullable', 'string'],
            'questions.*.options'                   => ['sometimes', 'array'],
            'questions.*.options.*.label'           => ['required_with:questions.*.options', 'string'],
            'questions.*.options.*.input_type'      => ['nullable', 'string', 'in:none,text,number'],
            'questions.*.options.*.input_placeholder' => ['nullable', 'string', 'max:255'],
            'questions.*.scale_from'                => ['nullable', 'integer'],
            'questions.*.scale_to'                  => ['nullable', 'integer'],
            'questions.*.scale_label_low'           => ['nullable', 'string', 'max:255'],
            'questions.*.scale_label_high'          => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sections.*.title'                      => 'Section title',
            'sections.*.description'                => 'Section description',
            'questions.*.label'                     => 'Question text',
            'questions.*.type'                      => 'Question type',
            'questions.*.sectionId'                 => 'Section',
            'questions.*.required'                  => 'Required field',
            'questions.*.description'               => 'Question description',
            'questions.*.options.*.label'           => 'Option label',
            'questions.*.options.*.input_type'      => 'Option input type',
            'questions.*.options.*.input_placeholder' => 'Option input placeholder',
        ];
    }
}
