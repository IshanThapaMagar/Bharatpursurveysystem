<?php

namespace App\Http\Controllers;

use App\Models\SurveySection;
use App\Models\Ward;
use App\Models\Response;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HouseDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $wards = Ward::orderBy('ward_no')->get();
        
        return view('housedescription.createdataform', compact('wards'));
    }

    /**
     * Get survey sections for a specific ward (AJAX)
     */
    public function getSectionsForWard($wardId)
    {
        $ward = Ward::findOrFail($wardId);
        
        $sections = SurveySection::with([
            'questions' => function ($query) {
                $query->ordered()
                    ->with([
                        'inputType',
                        'optionGroup.optionChoices' => function ($query) {
                            
                            $query->select('id', 'option_group_id', 'choice_text', 'custom_input_type', 'custom_input_placeholder');
                        },
                        'questionOptions.optionChoice' => function ($query) {
                            
                            $query->select('id', 'option_group_id', 'choice_text', 'custom_input_type', 'custom_input_placeholder');
                        }
                    ]);
            }
        ])
        ->forWard($wardId)
        ->ordered()
        ->get();

        return response()->json([
            'success' => true,
            'ward' => $ward,
            'sections' => $sections,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ward_id' => 'required|exists:wards,id',
                'answers' => 'required|array',
            ]);

            DB::beginTransaction();

            $response = Response::create([
                'user_id' => auth()->id(),
                'ward_id' => $validated['ward_id'],
                'submitted_at' => now(),
            ]);

            foreach ($request->input('answers', []) as $questionId => $answerData) {
                
                
                if (isset($answerData['question_option_id'])) {
                    $optionIds = $answerData['question_option_id'];
                    
                    
                    if (empty($optionIds)) {
                        continue;
                    }
                    
                    
                    if (is_array($optionIds)) {
                        foreach ($optionIds as $optionId) {
                            if (!empty($optionId)) {
                                $answerRecord = [
                                    'response_id' => $response->id,
                                    'question_id' => $questionId,
                                    'question_option_id' => $optionId,
                                ];
                                
                                
                                if (isset($answerData['custom_inputs'][$optionId]) && !empty($answerData['custom_inputs'][$optionId])) {
                                    $answerRecord['answer_text'] = $answerData['custom_inputs'][$optionId];
                                }
                                
                                Answer::create($answerRecord);
                            }
                        }
                    } 
                    
                    else {
                        $answerRecord = [
                            'response_id' => $response->id,
                            'question_id' => $questionId,
                            'question_option_id' => $optionIds,
                        ];
                        
                        // Handle custom input if exists
                        if (isset($answerData['custom_input']) && !empty($answerData['custom_input'])) {
                            $answerRecord['answer_text'] = $answerData['custom_input'];
                        }
                        
                        Answer::create($answerRecord);
                    }
                }
                
    
                elseif (isset($answerData['answer_text']) && !empty($answerData['answer_text'])) {
                    Answer::create([
                        'response_id' => $response->id,
                        'question_id' => $questionId,
                        'answer_text' => $answerData['answer_text'],
                    ]);
                }


                elseif (isset($answerData['answer_numeric']) && $answerData['answer_numeric'] !== null && $answerData['answer_numeric'] !== '') {
                    $answerRecord = [
                        'response_id' => $response->id,
                        'question_id' => $questionId,
                        'answer_numeric' => $answerData['answer_numeric'],
                    ];
                    

                    if (isset($answerData['unit_of_measure_id']) && !empty($answerData['unit_of_measure_id'])) {
                        $answerRecord['unit_of_measure_id'] = $answerData['unit_of_measure_id'];
                    }
                    
                    Answer::create($answerRecord);
                }
        
     
                elseif (isset($answerData['files']) && count($answerData['files']) > 0) {
                    foreach ($answerData['files'] as $file) {
                        $filePath = $file->store('survey-responses', 'public');
                        
                        Answer::create([
                            'response_id' => $response->id,
                            'question_id' => $questionId,
                            'answer_text' => $filePath,
                        ]);
                    }
                }
                
 
                elseif (isset($answerData['latitude']) && isset($answerData['longitude'])) {
                    if (!empty($answerData['latitude']) && !empty($answerData['longitude'])) {
     
                        Answer::create([
                            'response_id' => $response->id,
                            'question_id' => $questionId,
                            'answer_text' => json_encode([
                                'latitude' => floatval($answerData['latitude']),
                                'longitude' => floatval($answerData['longitude']),
                                'type' => 'location'
                            ]),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Survey submitted successfully!',
                'response_id' => $response->id,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the survey: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($wardId)
    // {
    //     $ward = Ward::findOrFail($wardId);
        
    //     $sections = SurveySection::with([
    //         'questions' => function ($query) {
    //             $query->ordered()
    //                 ->with([
    //                     'inputType',
    //                     'optionGroup.optionChoices',
    //                     'questionOptions.optionChoice'
    //                 ]);
    //         }
    //     ])
    //     ->forWard($wardId)
    //     ->ordered()
    //     ->get();

    //     return view('housedescription.createdataform', compact('sections', 'ward'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}