<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\DashboardCacheService;

class DashboardController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if ($authUser->isSuperAdmin()) {
            $wards = DB::table('wards')->orderBy('ward_no')->get();
            $selectedWard = request('ward', 'all');
        } else {
            $wards = DB::table('wards')->where('id', $authUser->ward_id)->get();
            $selectedWard = $authUser->ward_id;
        }

        $cacheKey = "dashboard_charts_{$selectedWard}";
        $charts = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(10), function() use ($selectedWard) {
            $allOptions = DB::table('questions as q')
                ->join('survey_sections as ss', 'ss.id', '=', 'q.survey_section_id')
                ->join('input_types as it', 'it.id', '=', 'q.input_type_id')
                ->join('question_options as qo', 'qo.question_id', '=', 'q.id')
                ->join('option_choices as oc', 'oc.id', '=', 'qo.option_choice_id')
                ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                    $q->where('ss.ward_id', $selectedWard);
                })
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
                ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                    $q->where('r.ward_id', $selectedWard);
                })
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

            return $results->groupBy('question_id');
        });

        $stats = \App\Models\DashboardStatistic::where('ward_id', (string)$selectedWard)->first();

        $ageGroups = $stats ? ($stats->age_groups ?? []) : [];
        $genderGroups = $stats ? ($stats->gender_groups ?? []) : [];
        $citizenshipGroups = $stats ? ($stats->citizenship_groups ?? []) : [];
        
        $motherTongueStats = $stats && $stats->mother_tongue_stats 
            ? collect($stats->mother_tongue_stats)->map(fn($item) => (object)$item) 
            : collect();
        $motherTongueTotal = $motherTongueStats->sum('total');

        $casteStats = $stats && $stats->caste_stats 
            ? collect($stats->caste_stats)->map(fn($item) => (object)$item) 
            : collect();
        $casteTotal = $casteStats->sum('total');

        $educationStats = $stats && $stats->education_stats 
            ? collect($stats->education_stats)->map(fn($item) => (object)$item) 
            : collect();
        $educationTotal = $educationStats->sum('total');

        $religionStats = $stats && $stats->religion_stats 
            ? collect($stats->religion_stats)->map(fn($item) => (object)$item) 
            : collect();
        $religionTotal = $religionStats->sum('total');

        $pinnedCharts = \App\Models\DashboardChart::where('user_id', auth()->id())->get();
        $chartsData = [];
        
        if ($pinnedCharts->isNotEmpty()) {
            $cacheKeyPinned = "dashboard_pinned_charts_{$selectedWard}_" . auth()->id();
            $chartsData = \Illuminate\Support\Facades\Cache::remember($cacheKeyPinned, now()->addMinutes(10), function() use ($pinnedCharts, $selectedWard) {
                $data = [];
                $pinnedQuestionIds = $pinnedCharts->pluck('question_id')->toArray();
                $questionTitles = DB::table('questions')->whereIn('id', $pinnedQuestionIds)->pluck('question_text', 'id');

                $optionsQuery = DB::table('question_options as qo')
                    ->join('option_choices as oc', 'oc.id', '=', 'qo.option_choice_id')
                    ->whereIn('qo.question_id', $pinnedQuestionIds)
                    ->select('qo.question_id', 'qo.id', 'oc.choice_text', 'oc.custom_input_type')
                    ->get()
                    ->groupBy('question_id')
                    ->map(function($items) { return $items->keyBy('id'); });

                $answersQuery = DB::table('answers as a')
                    ->join('responses as r', 'r.id', '=', 'a.response_id')
                    ->leftJoin('question_options as qo', 'qo.id', '=', 'a.question_option_id')
                    ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                        $q->where('r.ward_id', $selectedWard);
                    })
                    ->where(function($query) use ($pinnedQuestionIds) {
                        $query->whereIn('a.question_id', $pinnedQuestionIds)
                              ->orWhereIn('qo.question_id', $pinnedQuestionIds);
                    })
                    ->select('a.question_id as a_q_id', 'qo.question_id as qo_q_id', 'a.question_option_id', 'a.custom_input_value')
                    ->get();
                    
                $answersByQuestion = [];
                foreach ($answersQuery as $ans) {
                    $qId = $ans->a_q_id ?? $ans->qo_q_id;
                    if ($qId) {
                        $answersByQuestion[$qId][] = $ans;
                    }
                }

                foreach ($pinnedCharts as $pinned) {
                    $qId = $pinned->question_id;
                    $options = $optionsQuery->get($qId) ?? collect();
                    $answers = $answersByQuestion[$qId] ?? [];

                    $dataCounts = [];
                    foreach ($answers as $answer) {
                        $label = '';
                        if ($answer->question_option_id && $options->has($answer->question_option_id)) {
                            $option = $options->get($answer->question_option_id);
                            $label = $option->choice_text;
                            if (!empty(trim((string)$answer->custom_input_value))) {
                                $label .= ' (' . trim((string)$answer->custom_input_value) . ')';
                            }
                        } elseif (!empty(trim((string)$answer->custom_input_value))) {
                            $label = trim((string)$answer->custom_input_value);
                        }
                        
                        if (empty($label)) continue;
                        if (!isset($dataCounts[$label])) $dataCounts[$label] = 0;
                        $dataCounts[$label]++;
                    }
                    
                    if (!empty($dataCounts)) {
                        $data[$qId] = [
                            'title' => $pinned->custom_title ?? $questionTitles[$qId] ?? 'Unknown',
                            'labels' => array_keys($dataCounts),
                            'totals' => array_values($dataCounts),
                        ];
                    }
                }
                
                return $data;
            });
        }

        return view('dashboard', compact(
            'chartsData', 'wards', 'selectedWard', 
            'ageGroups', 'genderGroups', 'citizenshipGroups',
            'motherTongueStats', 'motherTongueTotal',
            'casteStats', 'casteTotal',
            'educationStats', 'educationTotal',
            'religionStats', 'religionTotal'
        ));
    }

    public function members(Request $request)
    {
        $authUser    = auth()->user();
        $ward        = $request->input('ward', 'all');
        $filterType  = $request->input('filter_type');
        
        if (!$authUser->isSuperAdmin()) {
            $ward = $authUser->ward_id;
        }

        $query = DB::table('house_members as hm')
            ->join('house_holders as hh', 'hh.id', '=', 'hm.house_holder_id')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($ward !== 'all', function ($q) use ($ward) {
                $q->where('r.ward_id', $ward);
            })
            
            ->leftJoin('gender_translations as gt', function($j) {
                $j->on('gt.gender_id', '=', 'hm.gender_id')->where('gt.locale', '=', 'np');
            })
            ->leftJoin('marital_status_translations as mst', function($j) {
                $j->on('mst.marital_status_id', '=', 'hm.marital_status_id')->where('mst.locale', '=', 'np');
            })
            ->leftJoin('education_level_translations as elt', function($j) {
                $j->on('elt.education_level_id', '=', 'hm.education_level_id')->where('elt.locale', '=', 'np');
            })
            ->leftJoin('health_status_translations as hst', function($j) {
                $j->on('hst.health_status_id', '=', 'hm.health_status_id')->where('hst.locale', '=', 'np');
            })
            ->leftJoin('institution_type_translations as itt', function($j) {
                $j->on('itt.institution_type_id', '=', 'hm.institution_type_id')->where('itt.locale', '=', 'np');
            })
            ->leftJoin('disability_translations as dt', function($j) {
                $j->on('dt.disability_id', '=', 'hm.disability_id')->where('dt.locale', '=', 'np');
            })
            ->select(
                'hm.id',
                'hm.full_name',
                'hm.age',
                'hm.dob_bs',
                DB::raw('COALESCE(gt.name, "—") as gender'),
                DB::raw('COALESCE(mst.name, "—") as marital_status'),
                DB::raw('COALESCE(elt.name, "—") as education_level'),
                DB::raw('COALESCE(hst.name, "—") as health_status'),
                DB::raw('COALESCE(itt.name, "—") as institution_type'),
                DB::raw('COALESCE(dt.name, "—") as disability')
            );


        if ($filterType === 'age_group') {
            $min = (int) $request->input('range_min', 0);
            $max = (int) $request->input('range_max', 200);
            if ($max === 200) {
                $query->where('hm.age', '>=', $min);
            } else {
                $query->whereBetween('hm.age', [$min, $max]);
            }
        } elseif ($filterType === 'gender') {
            $genderId = (int) $request->input('gender_id', 0);
            if ($genderId === 3) {
                $query->whereNotIn('hm.gender_id', [1, 2]);
            } else {
                $query->where('hm.gender_id', $genderId);
            }
        } elseif ($filterType === 'mother_tongue') {
            $query->where('hh.mother_tongue_id', (int) $request->input('id', 0));
        } elseif ($filterType === 'caste') {
            $query->where('hh.caste_id', (int) $request->input('id', 0));
        } elseif ($filterType === 'education') {
            $query->where('hm.education_level_id', (int) $request->input('id', 0));
        } elseif ($filterType === 'religion') {
            $query->where('hm.religion_id', (int) $request->input('id', 0));
        }

        $members = $query->orderBy('hm.full_name')->get();

        return view('dashboard.members', [
            'label'   => $request->input('label', 'सदस्यहरू'),
            'members' => $members,
        ]);
    }

    public function surveyReport(Request $request)
    {
        $authUser = auth()->user();
        if ($authUser->isSuperAdmin()) {
            $wards = DB::table('wards')->orderBy('ward_no')->get();
            $selectedWard = $request->input('ward') ?? ($wards->first()?->id ?? 0);
        } else {
            $wards = DB::table('wards')->where('id', $authUser->ward_id)->get();
            $selectedWard = $authUser->ward_id;
        }

       
        $questions = DB::table('questions as q')
            ->join('survey_sections as ss', 'ss.id', '=', 'q.survey_section_id')
            ->join('input_types as it', 'it.id', '=', 'q.input_type_id')
            ->where('ss.ward_id', $selectedWard)
            ->whereIn('it.input_type_name', ['radio', 'checkbox', 'dropdown', 'linear_scale'])
            ->select('q.id', 'q.question_text', 'q.order_index', 'it.input_type_name', 'q.scale_from', 'q.scale_to', 'q.scale_label_low', 'q.scale_label_high', 'ss.title as section_title', 'ss.order_index as section_order')
            ->orderBy('ss.order_index')
            ->orderBy('q.order_index')
            ->get();

        $questionIds = $questions->pluck('id')->toArray();
        $cacheKey = "survey_report_charts_v2_{$selectedWard}";
        
        $charts = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(10), function() use ($questions, $questionIds, $selectedWard) {
            $chartsData = [];
            if (empty($questionIds)) return $chartsData;

            $allOptions = DB::table('question_options as qo')
                ->join('option_choices as oc', 'oc.id', '=', 'qo.option_choice_id')
                ->whereIn('qo.question_id', $questionIds)
                ->select('qo.question_id', 'qo.id', 'oc.choice_text', 'oc.custom_input_type')
                ->get()
                ->groupBy('question_id')
                ->map(function($items) { return $items->keyBy('id'); });

            $allAnswers = DB::table('answers as a')
                ->join('responses as r', 'r.id', '=', 'a.response_id')
                ->leftJoin('question_options as qo', 'qo.id', '=', 'a.question_option_id')
                ->where('r.ward_id', $selectedWard)
                ->where(function($query) use ($questionIds) {
                    $query->whereIn('a.question_id', $questionIds)
                          ->orWhereIn('qo.question_id', $questionIds);
                })
                ->select('a.question_id as a_q_id', 'qo.question_id as qo_q_id', 'a.question_option_id', 'a.custom_input_value', 'a.answer_numeric')
                ->get();
            
            $answersByQuestion = [];
            foreach ($allAnswers as $ans) {
                $qId = $ans->a_q_id ?? $ans->qo_q_id;
                if ($qId) {
                    $answersByQuestion[$qId][] = $ans;
                }
            }

            foreach ($questions as $question) {
                $dataCounts = [];
                $labels = [];
                $totals = [];
                $chartType = $question->input_type_name === 'linear_scale' ? 'bar' : 'pie';
                $answers = $answersByQuestion[$question->id] ?? [];

                if ($question->input_type_name === 'linear_scale') {
                    $from = $question->scale_from ?? 1;
                    $to = $question->scale_to ?? 5;
                    for ($i = $from; $i <= $to; $i++) {
                        $dataCounts[$i] = 0;
                    }

                    foreach ($answers as $answer) {
                        if ($answer->answer_numeric !== null) {
                            $val = (int)$answer->answer_numeric;
                            if (isset($dataCounts[$val])) {
                                $dataCounts[$val]++;
                            }
                        }
                    }

                    $labels = array_map('strval', array_keys($dataCounts));
                    $totals = array_values($dataCounts);
                } else {
                    $options = $allOptions->get($question->id) ?? collect();

                    foreach ($answers as $answer) {
                        $label = '';
                        if ($answer->question_option_id && $options->has($answer->question_option_id)) {
                            $option = $options->get($answer->question_option_id);
                            $label = $option->choice_text;
                            if (!empty(trim((string)$answer->custom_input_value))) {
                                $label .= ' (' . trim((string)$answer->custom_input_value) . ')';
                            }
                        } elseif (!empty(trim((string)$answer->custom_input_value))) {
                            $label = trim((string)$answer->custom_input_value);
                        }
                        
                        if (empty($label)) continue;

                        if (!isset($dataCounts[$label])) {
                            $dataCounts[$label] = 0;
                        }
                        $dataCounts[$label]++;
                    }

                    $labels = array_keys($dataCounts);
                    $totals = array_values($dataCounts);
                }

                if (!empty($labels)) {
                    $chartsData[$question->id] = [
                        'question_id' => $question->id,
                        'section_title' => $question->section_title,
                        'question_text' => $question->question_text,
                        'labels' => $labels,
                        'totals' => $totals,
                        'chart_type' => $chartType,
                        'scale_label_low' => $question->scale_label_low ?? null,
                        'scale_label_high' => $question->scale_label_high ?? null,
                    ];
                }
            }
            
            return $chartsData;
        });
        
        $pinnedCharts = \App\Models\DashboardChart::where('user_id', auth()->id())->get()->keyBy('question_id');

        return view('survey-report', compact('charts', 'wards', 'selectedWard', 'pinnedCharts'));
    }
    
    public function pinChart(Request $request)
    {
        if (auth()->user()->isDataCollector()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'is_pinned' => 'required|boolean',
            'custom_title' => 'nullable|string|max:255'
        ]);

        if ($validated['is_pinned']) {
            \App\Models\DashboardChart::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'question_id' => $validated['question_id']
                ],
                [
                    'custom_title' => $validated['custom_title']
                ]
            );
        } else {
            \App\Models\DashboardChart::where('user_id', auth()->id())
                ->where('question_id', $validated['question_id'])
                ->delete();
        }

        // Bust this user's pinned-chart caches across all wards
        $wards = array_merge(['all'], DB::table('wards')->pluck('id')->toArray());
        $userId = auth()->id();
        foreach ($wards as $ward) {
            Cache::forget("dashboard_pinned_charts_{$ward}_{$userId}");
        }

        return response()->json(['success' => true]);
    }

    public function exportCsv(Request $request)
    {
        $authUser = auth()->user();
        $selectedWard = $request->input('ward', 'all');

        if (!$authUser->isSuperAdmin()) {
            $selectedWard = $authUser->ward_id;
        }

        $wardNoDisplay = 'All Wards';
        if ($selectedWard !== 'all') {
            $wardNoDisplay = DB::table('wards')->where('id', $selectedWard)->value('ward_no') ?? $selectedWard;
        }

        $stats = \App\Models\DashboardStatistic::where('ward_id', (string)$selectedWard)->first();

        $ageGroups = $stats ? ($stats->age_groups ?? []) : [];
        $genderGroups = $stats ? ($stats->gender_groups ?? []) : [];
        $citizenshipGroups = $stats ? ($stats->citizenship_groups ?? []) : [];
        
        $motherTongueStats = $stats && $stats->mother_tongue_stats 
            ? collect($stats->mother_tongue_stats)->map(fn($item) => (object)$item) 
            : collect();

        $casteStats = $stats && $stats->caste_stats 
            ? collect($stats->caste_stats)->map(fn($item) => (object)$item) 
            : collect();

        $educationStats = $stats && $stats->education_stats 
            ? collect($stats->education_stats)->map(fn($item) => (object)$item) 
            : collect();

        $religionStats = $stats && $stats->religion_stats 
            ? collect($stats->religion_stats)->map(fn($item) => (object)$item) 
            : collect();

        // --- Prepare CSV ---
        $filename = "dashboard_data_ward_{$selectedWard}_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($ageGroups, $genderGroups, $citizenshipGroups, $motherTongueStats, $casteStats, $educationStats, $religionStats, $wardNoDisplay) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header section
            fputcsv($file, ['BHARATPUR MAHANAGARPALIKA']);
            $wardDisplay = ($wardNoDisplay === 'All Wards') ? 'All Wards' : 'Ward No: ' . $wardNoDisplay;
            fputcsv($file, [$wardDisplay]);
            fputcsv($file, ['Report generated on: ' . date('Y-m-d H:i')]);
            fputcsv($file, []); // Empty row as separator

            fputcsv($file, ['Category', 'Label', 'Count', 'Percentage (%)']);

            // Age Groups
            foreach ($ageGroups as $group) {
                fputcsv($file, ['Age Group', $group['label'], $group['count'], $group['percentage']]);
            }
            fputcsv($file, []);

            // Gender
            foreach ($genderGroups as $group) {
                fputcsv($file, ['Gender', $group['label'], $group['count'], $group['percentage']]);
            }
            fputcsv($file, []);

            // Citizenship
            foreach ($citizenshipGroups as $group) {
                fputcsv($file, ['Residence (Household)', $group['label'], $group['count'], $group['percentage']]);
            }
            fputcsv($file, []);

            // Mother Tongue
            $totalMT = $motherTongueStats->sum('total') ?: 1;
            foreach ($motherTongueStats as $row) {
                fputcsv($file, ['Mother Tongue', $row->name, $row->total, round(($row->total / $totalMT) * 100, 2)]);
            }
            fputcsv($file, []);

            // Caste
            $totalCaste = $casteStats->sum('total') ?: 1;
            foreach ($casteStats as $row) {
                fputcsv($file, ['Caste', $row->name, $row->total, round(($row->total / $totalCaste) * 100, 2)]);
            }
            fputcsv($file, []);

            // Education
            $totalEdu = $educationStats->sum('total') ?: 1;
            foreach ($educationStats as $row) {
                fputcsv($file, ['Education', $row->label, $row->total, round(($row->total / $totalEdu) * 100, 2)]);
            }
            fputcsv($file, []);

            // Religion
            $totalRel = $religionStats->sum('total') ?: 1;
            foreach ($religionStats as $row) {
                fputcsv($file, ['Religion', $row->label, $row->total, round(($row->total / $totalRel) * 100, 2)]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}