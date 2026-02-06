<?php

namespace App\Http\Controllers;

use App\Models\SurveySection;
use App\Models\Question;
use App\Models\OptionGroup;
use App\Models\OptionChoice;
use App\Models\QuestionOption;
use App\Models\InputType;
use Illuminate\Http\Request;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SurveyBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
        
            $wards = Ward::select('id', 'ward_no')->get();
            $sections = collect();
        
            $validated = $request->validate([
                'ward_id' => 'nullable|integer|exists:wards,id',
            ]);


            if (!empty($validated['ward_id'])) {
                $sections = SurveySection::where('ward_id', $validated['ward_id'])
                    ->select('id', 'title', 'order_index') 
                    ->orderBy('order_index', 'asc')       
                    ->get();
            }

            return view('surveybuilder.managesections', compact('sections', 'wards'));

        } catch (\Illuminate\Validation\ValidationException $ve) {

            return redirect()->back()->withErrors($ve->errors())->withInput();
        } catch (\Exception $e) {
             
            return redirect()->back()->with('error', 'Something went wrong while fetching sections.');
        }
    }


    public function reorder(Request $request)
    {
        foreach ($request->order as $item) {
            SurveySection::where('id', $item['id'])->update(['order_index' => $item['order_index']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wards = Ward::select('id','ward_no')->get();
        return view('surveybuilder.createsurveyquestion',compact('wards'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ward_id' => 'required',
            'survey_data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()    
                            ->withErrors($validator) 
                            ->withInput();          
        }

        $surveyData = json_decode($request->input('survey_data'), true);

        if (!isset($surveyData['sections']) || !isset($surveyData['questions'])) {
            return redirect()->back()
                ->with('error', 'Invalid survey data format')
                ->withInput();
        }

        $dataValidator = Validator::make($surveyData, [
            'sections' => 'required|array|min:1',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.label' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.sectionId' => 'required|string',
            'questions.*.required' => 'nullable|boolean',
            'questions.*.description' => 'nullable|string',
            'questions.*.options' => 'array',
            'questions.*.options.*.label' => 'required_with:questions.*.options|string',
            'questions.*.options.*.input_type' => 'nullable|string|in:none,text,number',
            'questions.*.options.*.input_placeholder' => 'nullable|string|max:255',
            'questions.*.scale_from' => 'nullable|integer',
            'questions.*.scale_to' => 'nullable|integer|gte:questions.*.scale_from',
            'questions.*.scale_label_low' => 'nullable|string|max:255',
            'questions.*.scale_label_high' => 'nullable|string|max:255',
        ], [], [
            'sections.*.title' => 'Section title',
            'sections.*.description' => 'Section description',
            'questions.*.label' => 'Question text',
            'questions.*.type' => 'Question type',
            'questions.*.sectionId' => 'Section',
            'questions.*.required' => 'Required field',
            'questions.*.description' => 'Question description',
            'questions.*.options.*.label' => 'Option label',
            'questions.*.options.*.input_type' => 'Option input type',
            'questions.*.options.*.input_placeholder' => 'Option input placeholder',
        ]);

        if ($dataValidator->fails()) {
            return redirect()->back()
                ->withErrors($dataValidator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $wardIds = [];
            if ($request->ward_id === 'all') {
                $wardIds = Ward::pluck('id')->toArray();
            } else {
                $wardIds = [$request->ward_id];
            }

            foreach ($wardIds as $ward_id) {
                $sectionMap = [];
                $questionMap = [];

                foreach ($surveyData['sections'] as $index => $sectionData) {
                    $section = SurveySection::create([
                        'title' => $sectionData['title'],
                        'description' => $sectionData['description'] ?? null,
                        'ward_id' => $ward_id,
                        'order_index' => $index + 1,
                    ]);

                    $sectionMap[$sectionData['id']] = $section->id;
                }

                $questionIndex = 0;
                foreach ($surveyData['questions'] as $questionKey => $questionData) {
                    $questionIndex++;

                    $inputType = InputType::firstOrCreate(
                        ['input_type_name' => $questionData['type']]
                    );

                    $optionGroupId = null;

                    if (isset($questionData['options']) && !empty($questionData['options'])) {
                        $optionGroup = OptionGroup::create([
                            'option_group_name' => $questionData['label'] . ' Options - Ward ' . $ward_id
                        ]);
                        
                        $optionGroupId = $optionGroup->id;

                        foreach ($questionData['options'] as $optionData) {
                            OptionChoice::create([
                                'option_group_id' => $optionGroup->id,
                                'choice_text' => $optionData['label'],
                                'custom_input_type' => $optionData['input_type'] ?? 'none',
                                'custom_input_placeholder' => $optionData['input_placeholder'] ?? null,
                            ]);
                        }
                    }

                    $question = Question::create([
                        'survey_section_id' => $sectionMap[$questionData['sectionId']],
                        'question_text' => $questionData['label'],
                        'question_subtext' => $questionData['description'] ?? null,
                        'answer_required' => ($questionData['required'] ?? false) === true || ($questionData['required'] ?? false) == '1',
                        'input_type_id' => $inputType->id,
                        'option_group_id' => $optionGroupId,
                        'allow_multiple_option_answers' => in_array($questionData['type'], ['checkbox']),
                        'order_index' => $questionIndex,
                        'scale_from' => $questionData['scale_from'] ?? null,
                        'scale_to' => $questionData['scale_to'] ?? null,
                        'scale_label_low' => $questionData['scale_label_low'] ?? null,
                        'scale_label_high' => $questionData['scale_label_high'] ?? null,
                    ]);

                    $questionMap[$questionData['id']] = $question->id;

                    if ($optionGroupId) {
                        $optionChoices = OptionChoice::where('option_group_id', $optionGroupId)->get();
                        
                        foreach ($optionChoices as $optionChoice) {
                            QuestionOption::create([
                                'question_id' => $question->id,
                                'option_choice_id' => $optionChoice->id
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            $message = $request->ward_id === 'all' 
                ? 'Survey created successfully for all wards' 
                : 'Survey created successfully';

            return redirect()->back()
                            ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Survey creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create survey. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    try {
        $section = SurveySection::with([
            'questions.inputType',
            'questions.optionGroup.optionChoices',
            'questions.questionOptions.optionChoice'
        ])->findOrFail($id);

        $ward_id = $section->ward_id;
        $wards = Ward::select('id', 'ward_no')->get();

        $formattedSections = collect([
            [
                'id' => 's' . $section->id,
                'title' => $section->title,
                'description' => $section->description ?? '',
                'database_id' => $section->id,
            ]
        ]);

        $formattedQuestions = $section->questions->sortBy('order_index')->map(function($question) use ($section) {
            $formattedQuestion = [
                'id' => 'q' . $question->id,
                'sectionId' => 's' . $section->id,
                'type' => $question->inputType?->input_type_name ?? 'unknown',
                'label' => $question->question_text,
                'description' => $question->question_subtext ?? '',
                'required' => $question->answer_required,
                'database_id' => $question->id,
                'scale_from' => $question->scale_from,
                'scale_to' => $question->scale_to,
                'scale_label_low' => $question->scale_label_low,
                'scale_label_high' => $question->scale_label_high,
            ];

            if ($question->optionGroup?->optionChoices?->isNotEmpty()) {
                $formattedQuestion['options'] = $question->optionGroup->optionChoices->map(function($choice) {
                    return [
                        'id' => 'opt' . $choice->id,
                        'label' => $choice->choice_text,
                        'value' => strtolower(str_replace(' ', '_', $choice->choice_text)),
                        'input_type' => $choice->custom_input_type ?? 'none',
                        'input_placeholder' => $choice->custom_input_placeholder ?? '',
                        'database_id' => $choice->id
                    ];
                })->values()->toArray();
            }

            return $formattedQuestion;
        })->values();

        return view('surveybuilder.editsurveyquestion', compact('wards', 'ward_id', 'formattedSections', 'formattedQuestions', 'section'));

    } catch (\Exception $e) {
        return redirect()->route('surveyform.index')
            ->with('error', 'Failed to load survey for editing.');
    }
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
       
        $validator = Validator::make($request->all(), [
            'ward_id' => 'required|exists:wards,id',
            'survey_data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $surveyData = json_decode($request->input('survey_data'), true);

        if (!isset($surveyData['sections']) || !isset($surveyData['questions'])) {
            return redirect()->back()
                ->with('error', 'Invalid survey data format')
                ->withInput();
        }

        
        $dataValidator = Validator::make($surveyData, [
            'sections' => 'required|array|min:1',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.label' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.sectionId' => 'required|string',
            'questions.*.required' => 'nullable|boolean',
            'questions.*.description' => 'nullable|string',
            'questions.*.options' => 'array',
            'questions.*.options.*.label' => 'required_with:questions.*.options|string',
            'questions.*.options.*.input_type' => 'nullable|string|in:none,text,number',
            'questions.*.options.*.input_placeholder' => 'nullable|string|max:255',
            'questions.*.scale_from' => 'nullable|integer',
            'questions.*.scale_to' => 'nullable|integer|gte:questions.*.scale_from',
            'questions.*.scale_label_low' => 'nullable|string|max:255',
            'questions.*.scale_label_high' => 'nullable|string|max:255',
        ], [], [
            'sections.*.title' => 'Section title',
            'sections.*.description' => 'Section description',
            'questions.*.label' => 'Question text',
            'questions.*.type' => 'Question type',
            'questions.*.sectionId' => 'Section',
            'questions.*.required' => 'Required field',
            'questions.*.description' => 'Question description',
            'questions.*.options.*.label' => 'Option label',
            'questions.*.options.*.input_type' => 'Option input type',
            'questions.*.options.*.input_placeholder' => 'Option input placeholder',
        ]);

        if ($dataValidator->fails()) {
            return redirect()->back()
                ->withErrors($dataValidator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $section = SurveySection::findOrFail($id);
            $ward_id = $request->ward_id;

            $existingQuestions = Question::where('survey_section_id', $section->id)->get();
            
            foreach ($existingQuestions as $existingQuestion) {
                if ($existingQuestion->option_group_id) {
                    QuestionOption::where('question_id', $existingQuestion->id)->delete();                 
                    OptionChoice::where('option_group_id', $existingQuestion->option_group_id)->delete();
                    OptionGroup::where('id', $existingQuestion->option_group_id)->delete();
                }
            }

            Question::where('survey_section_id', $section->id)->delete();

            $sectionData = $surveyData['sections'][0]; 
            $section->update([
                'title' => $sectionData['title'],
                'description' => $sectionData['description'] ?? null,
            ]);

        
            $questionIndex = 0;
            foreach ($surveyData['questions'] as $questionData) {
                $questionIndex++;

                $inputType = InputType::firstOrCreate(
                    ['input_type_name' => $questionData['type']]
                );

                $optionGroupId = null;

                if (isset($questionData['options']) && !empty($questionData['options'])) {
                    $optionGroup = OptionGroup::create([
                        'option_group_name' => $questionData['label'] . ' Options'
                    ]);
                    
                    $optionGroupId = $optionGroup->id;

                    foreach ($questionData['options'] as $optionData) {
                        OptionChoice::create([
                            'option_group_id' => $optionGroup->id,
                            'choice_text' => $optionData['label'],
                            'custom_input_type' => $optionData['input_type'] ?? 'none',
                            'custom_input_placeholder' => $optionData['input_placeholder'] ?? null,
                        ]);
                    }
                }

                $question = Question::create([
                    'survey_section_id' => $section->id,
                    'question_text' => $questionData['label'],
                    'question_subtext' => $questionData['description'] ?? null,
                    'answer_required' => ($questionData['required'] ?? false) === true || ($questionData['required'] ?? false) == '1',
                    'input_type_id' => $inputType->id,
                    'option_group_id' => $optionGroupId,
                    'allow_multiple_option_answers' => in_array($questionData['type'], ['checkbox']),
                    'order_index' => $questionIndex,
                    'scale_from' => $questionData['scale_from'] ?? null,
                    'scale_to' => $questionData['scale_to'] ?? null,
                    'scale_label_low' => $questionData['scale_label_low'] ?? null,
                    'scale_label_high' => $questionData['scale_label_high'] ?? null,
                ]);
                if ($optionGroupId) {
                    $optionChoices = OptionChoice::where('option_group_id', $optionGroupId)->get();
                    
                    foreach ($optionChoices as $optionChoice) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_choice_id' => $optionChoice->id
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('surveyform.index', ['ward_id' => $ward_id])
                ->with('success', 'Section updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to update section. Please try again.')
                ->withInput();
        }
    }



    public function destroy(string $id)
    {
    }
}