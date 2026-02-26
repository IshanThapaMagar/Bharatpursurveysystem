<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Response;
use App\Models\SurveySection;
use App\Models\Ward;
use App\Models\Tole;

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
        $response = Response::findOrFail($id);
        $authUser = auth()->user();

        if (!$authUser->isSuperAdmin() && $authUser->ward_id != $response->ward_id) {
            abort(403, 'Unauthorized access to this response.');
        }

        // Normally you'd return an edit view here
        return view('responses.edit', compact('response'));
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

        // Logic to update response would go here
        
        return response()->json(['success' => true]);
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