<?php

namespace App\Http\Controllers;

use App\Models\SurveySection;
use App\Models\Ward;
use App\Models\Response;
use App\Models\Answer;
use App\Models\HouseHolder;
use App\Models\MotherTongue;
use App\Models\Caste;
use App\Models\Tole;
use App\Models\CitizenshipPermanentAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Services\DashboardCacheService;

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
            $wards = Ward::orderBy('ward_no')->select('id', 'ward_no')->get();
        } else {
            $wards = Ward::where('id', $authUser->ward_id)->select('id', 'ward_no')->get();
        }

        return view('housedescription.createdataform', compact('wards'));
    }

    /**
     * Fetch sections with questions and all nested relations for a given ward.
     * Cached per-ward for 60 minutes — survey structure rarely changes mid-session.
     */
    public function getSectionsData($wardId): array
    {
        return Cache::remember("survey_sections_ward_{$wardId}", now()->addMinutes(60), function () use ($wardId) {
            return SurveySection::with([
                'questions' => function ($query) {
                    $query->ordered()->with([
                        'inputType',
                        'optionGroup.optionChoices' => fn($q) => $q->select('id', 'option_group_id', 'choice_text', 'custom_input_type', 'custom_input_placeholder'),
                        'questionOptions.optionChoice' => fn($q) => $q->select('id', 'option_group_id', 'choice_text', 'custom_input_type', 'custom_input_placeholder'),
                    ]);
                },
            ])
            ->forWard($wardId)
            ->ordered()
            ->get()
            ->toArray();
        });
    }

    public function getLookupDataArray($wardId): array
    {
        $motherTongues = Cache::remember('lookup_mother_tongues', now()->addHours(24), fn() =>
            MotherTongue::select('id', 'name')->orderBy('name')->get()->toArray()
        );

        $castes = Cache::remember('lookup_castes', now()->addHours(24), fn() =>
            Caste::select('id', 'name')->orderBy('name')->get()->toArray()
        );

        $citizenshipAddresses = Cache::remember('lookup_citizenship_addresses', now()->addHours(24), fn() =>
            CitizenshipPermanentAddress::select('id', 'name')->orderBy('name')->get()->toArray()
        );

        $toles = Cache::remember("lookup_toles_ward_{$wardId}", now()->addHours(24), fn() =>
            Tole::where('ward_id', $wardId)->select('id', 'name', 'ward_id')->orderBy('name')->get()->toArray()
        );

        return [
            'mother_tongues'                  => $motherTongues,
            'castes'                          => $castes,
            'toles'                           => $toles,
            'citizenship_permanent_addresses' => $citizenshipAddresses,
        ];
    }

    /**
     * Show the survey wizard pre-loaded for a specific ward (server-side, no AJAX).
     */
    public function createWithWard($wardId)
    {
        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $wardId) {
            abort(403, 'Unauthorized access to this ward.');
        }

        $wardInfo   = Ward::select('id', 'ward_no')->findOrFail($wardId);
        $sections   = $this->getSectionsData($wardId);
        $lookupData = $this->getLookupDataArray($wardId);
        $wards      = Ward::orderBy('ward_no')->select('id', 'ward_no')->get();

        return view('housedescription.surveywizard', compact('wards', 'wardInfo', 'sections', 'lookupData'));
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
            }
                    ,
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
            // Re-use the same cached lookup arrays used by the server-side wizard.
            $lookupData = $this->getLookupDataArray($wardId);

            return response()->json([
                'success'                        => true,
                'mother_tongues'                 => $lookupData['mother_tongues'],
                'castes'                         => $lookupData['castes'],
                'toles'                          => $lookupData['toles'],
                'citizenship_permanent_addresses'=> $lookupData['citizenship_permanent_addresses'],
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


            $householder = HouseHolder::create([
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

            // ── Build answer rows in memory, then bulk-insert in one query ────
            $answerRows = [];
            $now = now()->toDateTimeString();

            foreach ($request->input('answers', []) as $questionId => $answerData) {

                if (isset($answerData['question_option_id'])) {
                    $optionIds = $answerData['question_option_id'];

                    if (empty($optionIds)) {
                        continue;
                    }

                    if (is_array($optionIds)) {
                        foreach ($optionIds as $optionId) {
                            if (!empty($optionId)) {
                                $row = [
                                    'response_id'        => $response->id,
                                    'question_id'        => $questionId,
                                    'question_option_id' => $optionId,
                                    'answer_text'        => null,
                                    'answer_numeric'     => null,
                                    'custom_input_value' => null,
                                    'unit_of_measure_id' => null,
                                    'created_at'         => $now,
                                    'updated_at'         => $now,
                                ];

                                if (!empty($answerData['custom_inputs'][$optionId])) {
                                    $row['custom_input_value'] = $answerData['custom_inputs'][$optionId];
                                }

                                $answerRows[] = $row;
                            }
                        }
                    } else {
                        $row = [
                            'response_id'        => $response->id,
                            'question_id'        => $questionId,
                            'question_option_id' => $optionIds,
                            'answer_text'        => null,
                            'answer_numeric'     => null,
                            'custom_input_value' => null,
                            'unit_of_measure_id' => null,
                            'created_at'         => $now,
                            'updated_at'         => $now,
                        ];

                        if (!empty($answerData['custom_input'])) {
                            $row['custom_input_value'] = $answerData['custom_input'];
                        }

                        $answerRows[] = $row;
                    }

                } elseif (!empty($answerData['answer_text'])) {
                    $answerRows[] = [
                        'response_id'        => $response->id,
                        'question_id'        => $questionId,
                        'question_option_id' => null,
                        'answer_text'        => $answerData['answer_text'],
                        'answer_numeric'     => null,
                        'custom_input_value' => null,
                        'unit_of_measure_id' => null,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];

                } elseif (isset($answerData['answer_numeric']) && $answerData['answer_numeric'] !== null && $answerData['answer_numeric'] !== '') {
                    $answerRows[] = [
                        'response_id'        => $response->id,
                        'question_id'        => $questionId,
                        'question_option_id' => null,
                        'answer_text'        => null,
                        'answer_numeric'     => $answerData['answer_numeric'],
                        'custom_input_value' => null,
                        'unit_of_measure_id' => !empty($answerData['unit_of_measure_id']) ? $answerData['unit_of_measure_id'] : null,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];

                } elseif (!empty($answerData['latitude']) && !empty($answerData['longitude'])) {
                    $answerRows[] = [
                        'response_id'        => $response->id,
                        'question_id'        => $questionId,
                        'question_option_id' => null,
                        'answer_text'        => json_encode([
                            'latitude'  => floatval($answerData['latitude']),
                            'longitude' => floatval($answerData['longitude']),
                            'type'      => 'location',
                        ]),
                        'answer_numeric'     => null,
                        'custom_input_value' => null,
                        'unit_of_measure_id' => null,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                    ];
                }
            }

            // Handle file uploads (must be stored individually before the bulk insert)
            $allFiles = $request->file('answers', []);
            foreach ($allFiles as $questionId => $fileData) {
                if (isset($fileData['files']) && is_array($fileData['files'])) {
                    foreach ($fileData['files'] as $file) {
                        $filePath = $file->store('survey-responses', 'public');
                        $answerRows[] = [
                            'response_id'        => $response->id,
                            'question_id'        => $questionId,
                            'question_option_id' => null,
                            'answer_text'        => $filePath,
                            'answer_numeric'     => null,
                            'custom_input_value' => null,
                            'unit_of_measure_id' => null,
                            'created_at'         => $now,
                            'updated_at'         => $now,
                        ];
                    }
                }
            }

            // Single bulk INSERT instead of N individual queries
            if (!empty($answerRows)) {
                DB::table('answers')->insert($answerRows);
            }

            DB::commit();

            // ── Real-time dashboard update ────────────────────────────────
            // Bust cached charts so the next dashboard load reads fresh data.
            DashboardCacheService::invalidate($validated['ward_id']);
            // Re-run stat aggregation for this ward in the background.
            Artisan::call('dashboard:aggregate-stats', ['--ward' => $validated['ward_id']]);

            return response()->json([
                'success' => true,
                'message' => 'Survey submitted successfully!',
            ]);

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
        catch (\Exception $e) {
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