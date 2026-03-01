<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\SurveySection;
use App\Models\Ward;
use App\Models\Tole;
use App\Models\Caste;
use App\Models\MotherTongue;
use App\Models\CitizenshipPermanentAddress;
use App\Models\Householder;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();
        
        $responsesQuery = Response::with(['householder' => function($query) {
            $query->withCount('members')->with('tole');
        }]);

        if (!$authUser->isSuperAdmin()) {
            $responsesQuery->where('ward_id', $authUser->ward_id);
            $wards = Ward::where('id', $authUser->ward_id)->get();
        } else {
            $wards = Ward::orderBy('ward_no')->get();
        }

        $responses = $responsesQuery->get();
        
        $tolesQuery = Tole::select('name')->distinct()->orderBy('name');
        if (!$authUser->isSuperAdmin()) {
            $tolesQuery->where('ward_id', $authUser->ward_id);
        }
        $toles = $tolesQuery->get();

        return view('responses.surveyresponses', compact('responses', 'wards', 'toles'));
    }

    public function getTolesByWard(Request $request)
    {
        $toles = $request->filled('ward_id')
            ? Tole::where('ward_id', $request->ward_id)->orderBy('name')->get(['id', 'name'])
            : Tole::select('name')->distinct()->orderBy('name')->get(['name as id', 'name']);

        return response()->json($toles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $response = Response::with([
            'householder.caste',
            'householder.motherTongue',
            'householder.tole',
            'householder.members' => function ($query) {
                $query->with([
                    'relationship.translations',
                    'gender.translations',
                    'maritalStatus.translations',
                    'educationLevel.translations',
                    'institutionType.translations',
                    'healthStatus.translations',
                    'district.translations',
                    'bloodGroup.translations',
                    'disability.translations',
                    'governmentSupportType.translations',
                    'nativeSpeakingLevel.translations',
                ]);
            },
            'answers.questionOption.question.surveySection',
            'answers.questionOption.optionChoice',
            'answers.unitOfMeasure',
        ])->findOrFail($id);

        $authUser = auth()->user();
        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $response->ward_id) {
            abort(403, 'Unauthorized access to this response.');
        }

        $household = $response->householder;

        $answerMap = [];
        foreach ($response->answers as $answer) {
            $questionId = $answer->question_id;
            
          
            if (!$questionId && $answer->questionOption) {
                $questionId = $answer->questionOption->question_id;
            }

            if (!$questionId) {
                continue;
            }

            $value = null;

            if ($answer->answer_text !== null) {
                $text = $answer->answer_text;

                if (str_starts_with($text, '{') && str_contains($text, '"type":"location"')) {
                    $loc = json_decode($text, true);
                    if ($loc && isset($loc['latitude'], $loc['longitude'])) {
                        $value = "Lat: {$loc['latitude']}, Long: {$loc['longitude']}";
                    } else {
                        $value = $text;
                    }
                } 
  
                elseif (str_starts_with($text, 'survey-responses/')) {
                    $url = asset('storage/' . $text);
                    $filename = basename($text);
                    $value = "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-600 hover:underline\">View File</a>";
                }
                else {
                    $value = $text;
                }
            } elseif ($answer->answer_numeric !== null) {
                $unit  = $answer->unitOfMeasure?->name ?? '';
                $value = $answer->answer_numeric . ($unit ? ' ' . $unit : '');
            } elseif ($answer->questionOption?->optionChoice) {
                $value = $answer->questionOption->optionChoice->choice_text;
            }

            if ($answer->custom_input_value) {
                $value = $value ? "$value ({$answer->custom_input_value})" : $answer->custom_input_value;
            }

            $value = $value ?? '—';

            if (isset($answerMap[$questionId])) {
                $answerMap[$questionId] .= ', ' . $value;
            } else {
                $answerMap[$questionId] = $value;
            }
        }


        $sections = SurveySection::with([
            'questions' => fn ($q) => $q->ordered()->with('inputType'),
        ])
        ->forWard($response->ward_id)
        ->ordered()
        ->get()
        ->map(function ($section) use ($answerMap) {
            $section->questions->each(function ($question) use ($answerMap) {
                $question->resolved_answer = $answerMap[$question->id] ?? null;
            });
            return $section;
        });

        $noAnswersFound = $response->answers->isEmpty();

        return view('responses.showdetails', compact(
            'response',
            'household',
            'sections',
            'noAnswersFound',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = Response::with([
            'householder.tole',
            'householder.caste',
            'householder.motherTongue',
            'answers.question.inputType',
            'answers.question.questionOptions.optionChoice',
            'answers.questionOption.optionChoice',
            'answers.unitOfMeasure',
        ])->findOrFail($id);

        $authUser = auth()->user();

        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $response->ward_id) {
            abort(403, 'Unauthorized access to this response.');
        }

        // Build answer map keyed by question_id
        // For checkbox/multi-select: stores an array of question_option_ids
        // For radio/dropdown: stores the single question_option_id
        // For text/numeric/location: stores the raw value
        $answerMap = [];       // question_id => raw value(s) for pre-filling form fields
        $displayMap = [];      // question_id => human-readable string (for read-only display)
        $customInputMap = [];  // question_id => [ option_id => custom_input_value ]

        foreach ($response->answers as $answer) {
            $questionId = $answer->question_id;

            if (!$questionId && $answer->questionOption) {
                $questionId = $answer->questionOption->question_id;
            }

            if (!$questionId) {
                continue;
            }

            $inputTypeName = $answer->question?->inputType?->input_type_name
                ?? $answer->questionOption?->question?->inputType?->input_type_name
                ?? null;

            // --- Build editable answer map ---
            if ($answer->question_option_id !== null) {
                if ($inputTypeName === 'checkbox') {
                    // Checkbox: collect all selected option IDs as an array
                    if (!isset($answerMap[$questionId])) {
                        $answerMap[$questionId] = [];
                    }
                    $answerMap[$questionId][] = (string) $answer->question_option_id;
                } else {
                    // Radio / dropdown: single option ID
                    $answerMap[$questionId] = (string) $answer->question_option_id;
                }

                // Custom input per option
                if ($answer->custom_input_value) {
                    if (!isset($customInputMap[$questionId])) {
                        $customInputMap[$questionId] = [];
                    }
                    $customInputMap[$questionId][$answer->question_option_id] = $answer->custom_input_value;
                }
            } elseif ($answer->answer_text !== null) {
                $answerMap[$questionId] = $answer->answer_text; // text / date / email / location JSON
            } elseif ($answer->answer_numeric !== null) {
                $answerMap[$questionId] = $answer->answer_numeric; // number / linear_scale
            }

            // --- Build human-readable display map (used in fallback rows) ---
            $displayValue = null;
            if ($answer->answer_text !== null) {
                $text = $answer->answer_text;
                if (str_starts_with($text, '{') && str_contains($text, '"type":"location"')) {
                    $loc = json_decode($text, true);
                    $displayValue = ($loc && isset($loc['latitude'], $loc['longitude']))
                        ? "Lat: {$loc['latitude']}, Long: {$loc['longitude']}"
                        : $text;
                } elseif (str_starts_with($text, 'survey-responses/')) {
                    $url = asset('storage/' . $text);
                    $filename = basename($text);
                    $displayValue = "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-600 hover:underline\">View File</a>";
                } else {
                    $displayValue = $text;
                }
            } elseif ($answer->answer_numeric !== null) {
                $unit = $answer->unitOfMeasure?->unit_of_measures_name ?? '';
                $displayValue = $answer->answer_numeric . ($unit ? ' ' . $unit : '');
            } elseif ($answer->questionOption?->optionChoice) {
                $displayValue = $answer->questionOption->optionChoice->choice_text;
            }

            if ($answer->custom_input_value) {
                $displayValue = $displayValue
                    ? "$displayValue ({$answer->custom_input_value})"
                    : $answer->custom_input_value;
            }

            $displayValue = $displayValue ?? '—';

            if (isset($displayMap[$questionId])) {
                $displayMap[$questionId] .= ', ' . $displayValue;
            } else {
                $displayMap[$questionId] = $displayValue;
            }
        }

        // Load ordered sections with questions and their options for this ward
        $sections = SurveySection::with([
            'questions' => fn ($q) => $q->ordered()->with([
                'inputType',
                'questionOptions.optionChoice',
            ]),
        ])
        ->forWard($response->ward_id)
        ->ordered()
        ->get()
        ->map(function ($section) use ($answerMap, $displayMap, $customInputMap) {
            $section->questions->each(function ($question) use ($answerMap, $displayMap, $customInputMap) {
                $question->prefill_value   = $answerMap[$question->id]     ?? null;
                $question->display_answer  = $displayMap[$question->id]    ?? null;
                $question->custom_inputs   = $customInputMap[$question->id] ?? [];
            });
            return $section;
        });

        $household = $response->householder;

        // Lookup data for householder form dropdowns
        $castes                 = Caste::orderBy('name')->get(['id', 'name']);
        $motherTongues          = MotherTongue::orderBy('name')->get(['id', 'name']);
        $citizenshipAddresses   = CitizenshipPermanentAddress::orderBy('name')->get(['id', 'name']);
        $toles                  = Tole::where('ward_id', $response->ward_id)->orderBy('name')->get(['id', 'name']);

        return view('responses.edit', compact(
            'response',
            'household',
            'sections',
            'answerMap',
            'customInputMap',
            'castes',
            'motherTongues',
            'citizenshipAddresses',
            'toles',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $response = Response::findOrFail($id);
        $authUser = auth()->user();

        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $response->ward_id) {
            abort(403, 'Unauthorized access to this response.');
        }

        // ── Update householder information ──────────────────────────────────
        if ($response->householder_id && $request->has('householder')) {
            $hData = $request->input('householder', []);

            $updateData = array_filter([
                'householder_name'               => $hData['householder_name']               ?? null,
                'father_name'                    => $hData['father_name']                    ?? null,
                'mother_name'                    => $hData['mother_name']                    ?? null,
                'mother_tongue_id'               => $hData['mother_tongue_id']               ?? null,
                'caste_id'                       => $hData['caste_id']                       ?? null,
                'tole_id'                        => $hData['tole_id']                        ?? null,
                'house_number'                   => $hData['house_number']                   ?? null,
                'lot_number'                     => $hData['lot_number']                     ?? null,
                'phone_number'                   => $hData['phone_number']                   ?? null,
                'citizenship_permanent_address_id' => $hData['citizenship_permanent_address_id'] ?? null,
            ], fn ($v) => $v !== null && $v !== '');

            // Handle profile photo replacement
            if ($request->hasFile('householder.profile_photo')) {
                $updateData['profile_photo'] = $request->file('householder.profile_photo')
                    ->store('householder-photos', 'public');
            }

            if (!empty($updateData)) {
                Householder::where('id', $response->householder_id)->update($updateData);
            }
        }

        $answersPayload = $request->input('answers', []);

        foreach ($answersPayload as $questionId => $data) {
            $questionId = (int) $questionId;

            // Resolve the input type name for this question
            $question  = \App\Models\Question::with('inputType')->find($questionId);
            $typeName  = $question?->inputType?->input_type_name ?? 'short_text';

            // --------------- Option-based types (radio / dropdown / checkbox) ---------------
            if (in_array($typeName, ['radio', 'dropdown', 'checkbox'])) {
                // Remove all old answers for this question on this response
                \App\Models\Answer::where('response_id', $response->id)
                    ->where('question_id', $questionId)
                    ->delete();

                $selectedOptionIds = (array) ($data['question_option_id'] ?? []);
                $customInputs      = (array) ($data['custom_inputs'] ?? []);

                foreach ($selectedOptionIds as $optionId) {
                    $optionId = (int) $optionId;
                    if (!$optionId) continue;

                    \App\Models\Answer::create([
                        'response_id'        => $response->id,
                        'question_id'        => $questionId,
                        'question_option_id' => $optionId,
                        'custom_input_value' => $customInputs[$optionId] ?? null,
                    ]);
                }

            // --------------- Linear scale / number ---------------
            } elseif ($typeName === 'linear_scale' || $typeName === 'number') {
                $numericValue = isset($data['answer_numeric']) && $data['answer_numeric'] !== ''
                    ? $data['answer_numeric']
                    : null;

                \App\Models\Answer::updateOrCreate(
                    ['response_id' => $response->id, 'question_id' => $questionId],
                    [
                        'answer_numeric'  => $numericValue,
                        'answer_text'     => null,
                        'question_option_id' => null,
                        'custom_input_value' => null,
                    ]
                );

            // --------------- Location ---------------
            } elseif ($typeName === 'location') {
                $lat = $data['latitude'] ?? null;
                $lng = $data['longitude'] ?? null;

                $locationJson = ($lat !== null && $lng !== null)
                    ? json_encode(['type' => 'location', 'latitude' => $lat, 'longitude' => $lng])
                    : ($data['answer_text'] ?? null);

                \App\Models\Answer::updateOrCreate(
                    ['response_id' => $response->id, 'question_id' => $questionId],
                    [
                        'answer_text'        => $locationJson,
                        'answer_numeric'     => null,
                        'question_option_id' => null,
                        'custom_input_value' => null,
                    ]
                );

            // --------------- File upload ---------------
            } elseif ($typeName === 'file') {
                if ($request->hasFile("answers.{$questionId}.file")) {
                    $path = $request->file("answers.{$questionId}.file")
                        ->store('survey-responses', 'public');

                    \App\Models\Answer::updateOrCreate(
                        ['response_id' => $response->id, 'question_id' => $questionId],
                        [
                            'answer_text'        => $path,
                            'answer_numeric'     => null,
                            'question_option_id' => null,
                            'custom_input_value' => null,
                        ]
                    );
                }
                // If no new file, keep existing (existing_file hidden input is informational only)

            // --------------- Text-based (short_text, long_text, email, date …) ---------------
            } else {
                $textValue = isset($data['answer_text']) && $data['answer_text'] !== ''
                    ? $data['answer_text']
                    : null;

                \App\Models\Answer::updateOrCreate(
                    ['response_id' => $response->id, 'question_id' => $questionId],
                    [
                        'answer_text'        => $textValue,
                        'answer_numeric'     => null,
                        'question_option_id' => null,
                        'custom_input_value' => null,
                    ]
                );
            }
        }

        // Respond appropriately for AJAX or standard form submit
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Response updated successfully.']);
        }

        return redirect()
            ->route('survey-responses.show', $response->id)
            ->with('success', 'सर्वेक्षण उत्तरहरू सफलतापूर्वक अद्यावधिक गरियो।');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = Response::findOrFail($id);
        $authUser = auth()->user();

        // Only Superadmin or Ward Admin of the specific ward can delete
        if (!$authUser->isSuperAdmin() && !($authUser->isWardAdmin() && $authUser->ward_id == $response->ward_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this response.'
            ], 403);
        }

        $response->delete();

        return response()->json([
            'success' => true,
            'message' => 'Response deleted successfully.'
        ]);
    }
}