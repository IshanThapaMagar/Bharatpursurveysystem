<?php

namespace App\Http\Controllers;

use App\Models\SurveySection;
use App\Models\Ward;
use App\Models\Response;
use App\Models\Answer;
use App\Models\Householder;
use App\Models\MotherTongue;
use App\Models\Caste;
use App\Models\Tole;
use App\Models\CitizenshipPermanentAddress;
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


    public function create()
    {
        $authUser = auth()->user();
        if ($authUser->isSuperAdmin()) {
            $wards = Ward::orderBy('ward_no')->get();
        } else {
            $wards = Ward::where('id', $authUser->ward_id)->get();
        }
        
        return view('housedescription.createdataform', compact('wards'));
    }

    public function getSectionsForWard($wardId)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $wardId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this ward.',
            ], 403);
        }

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

    public function getLookupData($wardId)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $wardId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this ward.',
            ], 403);
        }

        try {
            $motherTongues = MotherTongue::all();
            $castes = Caste::all();
            $toles = Tole::where('ward_id', $wardId)->get();
            $citizenshipAddresses = CitizenshipPermanentAddress::all();

            return response()->json([
                'success' => true,
                'mother_tongues' => $motherTongues,
                'castes' => $castes,
                'toles' => $toles,
                'citizenship_permanent_addresses' => $citizenshipAddresses,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch lookup data',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
        try {
            $validated = $request->validate([
                'ward_id' => 'required|exists:wards,id',
                'householder_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'mother_tongue_id' => 'required|exists:mother_tongues,id',
                'caste_id' => 'required|exists:castes,id',
                'tole_id' => 'required|exists:toles,id',
                'ward_no' => 'required|integer',
                'lot_number' => 'required|string|max:50',
                'house_number' => 'required|string|max:255',
                'phone_number' => 'required|string|size:10',
                'citizenship_permanent_address_id' => 'required|exists:citizenship_permanent_addresses,id',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'answers' => 'required|array',
            ]);

            $authUser = auth()->user();
            if (!$authUser->isSuperAdmin() && $authUser->ward_id != $validated['ward_id']) {
                abort(403, 'You do not have permission to submit data for this ward.');
            }

            DB::beginTransaction();

            
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->store('householder-photos', 'public');
            }

            
            $householder = Householder::create([
                'householder_name' => $validated['householder_name'],
                'father_name' => $validated['father_name'],
                'mother_name' => $validated['mother_name'],
                'mother_tongue_id' => $validated['mother_tongue_id'],
                'caste_id' => $validated['caste_id'],
                'tole_id' => $validated['tole_id'],
                'ward_no' => $validated['ward_no'],
                'lot_number' => $validated['lot_number'],
                'house_number' => $validated['house_number'],
                'phone_number' => $validated['phone_number'],
                'citizenship_permanent_address_id' => $validated['citizenship_permanent_address_id'],
                'profile_photo' => $profilePhotoPath,
            ]);


            $response = Response::create([
                'user_id' => auth()->id(),
                'ward_id' => $validated['ward_id'],
                'householder_id' => $householder->id,
                'submitted_at' => now(),
            ]);

            // Process answers
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
                                    $answerRecord['custom_input_value'] = $answerData['custom_inputs'][$optionId];
                                }
                                
                                Answer::create($answerRecord);
                            }
                        }
                    } else {
                        $answerRecord = [
                            'response_id' => $response->id,
                            'question_id' => $questionId,
                            'question_option_id' => $optionIds,
                        ];
                        
                        if (isset($answerData['custom_input']) && !empty($answerData['custom_input'])) {
                            $answerRecord['custom_input_value'] = $answerData['custom_input'];
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

            // Handle files separately as they are in $request->file()
            $allFiles = $request->file('answers', []);
            foreach ($allFiles as $questionId => $fileData) {
                if (isset($fileData['files']) && is_array($fileData['files'])) {
                    foreach ($fileData['files'] as $file) {
                        $filePath = $file->store('survey-responses', 'public');
                        Answer::create([
                            'response_id' => $response->id,
                            'question_id' => $questionId,
                            'answer_text' => $filePath,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Survey submitted successfully!',
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