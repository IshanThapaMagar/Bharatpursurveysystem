<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $wards = DB::table('wards')->orderBy('ward_no')->get();
        $selectedWard = request('ward') ?? $wards->first()?->id;

        $allOptions = DB::table('questions as q')
            ->join('survey_sections as ss', 'ss.id', '=', 'q.survey_section_id')
            ->join('input_types as it', 'it.id', '=', 'q.input_type_id')
            ->join('question_options as qo', 'qo.question_id', '=', 'q.id')
            ->join('option_choices as oc', 'oc.id', '=', 'qo.option_choice_id')
            ->where('ss.ward_id', $selectedWard)
            ->whereIn('it.input_type_name', ['radio', 'checkbox', 'dropdown'])
            ->select(
                'q.id as question_id',
                'q.question_text',
                'qo.id as question_option_id',
                'oc.choice_text'
            )
            ->get();

        $answerCounts = DB::table('answers as a')
            ->join('responses as r', 'r.id', '=', 'a.response_id')
            ->where('r.ward_id', $selectedWard)
            ->select(
                'a.question_option_id',
                DB::raw('COUNT(a.id) as total')
            )
            ->groupBy('a.question_option_id')
            ->pluck('total', 'question_option_id');

       
        $results = $allOptions->map(function ($option) use ($answerCounts) {
            return (object)[
                'question_id' => $option->question_id,
                'question_text' => $option->question_text,
                'choice_text' => $option->choice_text,
                'total' => $answerCounts[$option->question_option_id] ?? 0
            ];
        });

        $charts = $results->groupBy('question_id');

        return view('dashboard', compact('charts', 'wards', 'selectedWard'));
    }
}