<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $charts = $results->groupBy('question_id');

        $ageStats = DB::table('house_members as hm')
            ->join('house_holders as hh', 'hh.id', '=', 'hm.house_holder_id')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                $q->where('r.ward_id', $selectedWard);
            })
            ->selectRaw("
                COUNT(CASE WHEN hm.age BETWEEN 0 AND 5 THEN 1 END) as group1,
                COUNT(CASE WHEN hm.age BETWEEN 6 AND 16 THEN 1 END) as group2,
                COUNT(CASE WHEN hm.age BETWEEN 17 AND 32 THEN 1 END) as group3,
                COUNT(CASE WHEN hm.age BETWEEN 33 AND 54 THEN 1 END) as group4,
                COUNT(CASE WHEN hm.age BETWEEN 55 AND 65 THEN 1 END) as group5,
                COUNT(CASE WHEN hm.age > 65 THEN 1 END) as group6,
                COUNT(hm.id) as total_members
            ")
            ->first();

        $ageGroups = [
            [
                'label' => __('Infant (0-5)'),
                'range' => '(०-५)',
                'count' => $ageStats->group1,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group1 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-rose-500',
                'light_color' => 'bg-rose-100',
                'border_color' => 'border-rose-200'
            ],
            [
                'label' => __('Children (6-16)'),
                'range' => '(६-१६)',
                'count' => $ageStats->group2,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group2 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-blue-500',
                'light_color' => 'bg-blue-100',
                'border_color' => 'border-blue-200'
            ],
            [
                'label' => __('Youth (17-32)'),
                'range' => '(१७-३२)',
                'count' => $ageStats->group3,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group3 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-amber-500',
                'light_color' => 'bg-amber-100',
                'border_color' => 'border-amber-200'
            ],
            [
                'label' => __('Adult (33-54)'),
                'range' => '(३३-५४)',
                'count' => $ageStats->group4,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group4 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-emerald-500',
                'light_color' => 'bg-emerald-100',
                'border_color' => 'border-emerald-200'
            ],
            [
                'label' => __('Elderly (55-65)'),
                'range' => '(५५-६५)',
                'count' => $ageStats->group5,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group5 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-violet-500',
                'light_color' => 'bg-violet-100',
                'border_color' => 'border-violet-200'
            ],
            [
                'label' => __('Senior Citizen (65+)'),
                'range' => '(६५ - माथि)',
                'count' => $ageStats->group6,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group6 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-cyan-500',
                'light_color' => 'bg-cyan-100',
                'border_color' => 'border-cyan-200'
            ],
        ];

        $genderStats = DB::table('house_members as hm')
            ->join('house_holders as hh', 'hh.id', '=', 'hm.house_holder_id')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                $q->where('r.ward_id', $selectedWard);
            })
            ->selectRaw("
                COUNT(CASE WHEN hm.gender_id = 1 THEN 1 END) as male,
                COUNT(CASE WHEN hm.gender_id = 2 THEN 1 END) as female,
                COUNT(CASE WHEN hm.gender_id NOT IN (1, 2) THEN 1 END) as other,
                COUNT(hm.id) as total_members
            ")
            ->first();

        $genderGroups = [
            [
                'label' => __('Male'),
                'count' => $genderStats->male,
                'percentage' => $genderStats->total_members > 0 ? round(($genderStats->male / $genderStats->total_members) * 100, 2) : 0,
                'color' => 'bg-blue-600',
                'light_color' => 'bg-blue-50',
                'border_color' => 'border-blue-200'
            ],
            [
                'label' => __('Female'),
                'count' => $genderStats->female,
                'percentage' => $genderStats->total_members > 0 ? round(($genderStats->female / $genderStats->total_members) * 100, 2) : 0,
                'color' => 'bg-pink-600',
                'light_color' => 'bg-pink-50',
                'border_color' => 'border-pink-200'
            ],
            [
                'label' => __('Other'),
                'count' => $genderStats->other,
                'percentage' => $genderStats->total_members > 0 ? round(($genderStats->other / $genderStats->total_members) * 100, 2) : 0,
                'color' => 'bg-orange-600',
                'light_color' => 'bg-orange-50',
                'border_color' => 'border-orange-200'
            ],
        ];

        $citStats = DB::table('house_holders as hh')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                $q->where('r.ward_id', $selectedWard);
            })
            ->selectRaw("
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 1 THEN 1 END) as stat1,
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 2 THEN 1 END) as stat2,
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 3 THEN 1 END) as stat3,
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 4 THEN 1 END) as stat4,
                COUNT(hh.id) as total_householders
            ")
            ->first();

        $citizenshipGroups = [
            [
                'id' => 1,
                'label' => 'स्थायी जन्म',
                'count' => $citStats->stat1,
                'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat1 / $citStats->total_householders) * 100, 2) : 0,
                'color' => 'bg-teal-500',
                'light_color' => 'bg-teal-50',
                'border_color' => 'border-teal-200'
            ],
            [
                'id' => 2,
                'label' => 'बसाईसराई',
                'count' => $citStats->stat2,
                'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat2 / $citStats->total_householders) * 100, 2) : 0,
                'color' => 'bg-indigo-500',
                'light_color' => 'bg-indigo-50',
                'border_color' => 'border-indigo-200'
            ],
            [
                'id' => 3,
                'label' => 'अस्थायी बसोबास',
                'count' => $citStats->stat3,
                'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat3 / $citStats->total_householders) * 100, 2) : 0,
                'color' => 'bg-fuchsia-500',
                'light_color' => 'bg-fuchsia-50',
                'border_color' => 'border-fuchsia-200'
            ],
            [
                'id' => 4,
                'label' => 'बसाईसराईको निसा नभएको',
                'count' => $citStats->stat4,
                'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat4 / $citStats->total_householders) * 100, 2) : 0,
                'color' => 'bg-slate-500',
                'light_color' => 'bg-slate-50',
                'border_color' => 'border-slate-200'
            ],
        ];

       
        $motherTongueStats = DB::table('mother_tongues as mt')
            ->leftJoin('house_holders as hh', function($join) use ($selectedWard) {
                $join->on('hh.mother_tongue_id', '=', 'mt.id')
                    ->whereIn('hh.id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                                $q->where('ward_id', $selectedWard);
                            });
                    });
            })
            ->leftJoin('house_members as hm', 'hm.house_holder_id', '=', 'hh.id')
            ->select('mt.id', 'mt.name', DB::raw('COUNT(hm.id) as total'))
            ->groupBy('mt.id', 'mt.name')
            ->orderByDesc('total')
            ->get();

        $motherTongueTotal = $motherTongueStats->sum('total');

        
        $casteStats = DB::table('castes as c')
            ->leftJoin('house_holders as hh', function($join) use ($selectedWard) {
                $join->on('hh.caste_id', '=', 'c.id')
                    ->whereIn('hh.id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                                $q->where('ward_id', $selectedWard);
                            });
                    });
            })
            ->leftJoin('house_members as hm', 'hm.house_holder_id', '=', 'hh.id')
            ->select('c.id', 'c.name', DB::raw('COUNT(hm.id) as total'))
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total')
            ->get();

        $casteTotal = $casteStats->sum('total');

 
        $educationStats = DB::table('education_levels as el')
            ->leftJoin('education_level_translations as elt', function ($join) {
                $join->on('elt.education_level_id', '=', 'el.id')
                     ->where('elt.locale', '=', 'np');
            })
            ->leftJoin('house_members as hm', function($join) use ($selectedWard) {
                $join->on('hm.education_level_id', '=', 'el.id')
                    ->whereIn('hm.house_holder_id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                                $q->where('ward_id', $selectedWard);
                            });
                    });
            })
            ->select(
                'el.id',
                DB::raw('COALESCE(MAX(elt.name), MAX(CAST(el.id AS CHAR))) as label'),
                DB::raw('COUNT(hm.id) as total')
            )
            ->groupBy('el.id')
            ->orderByDesc('total')
            ->get();

        $educationTotal = $educationStats->sum('total');


        $religionStats = DB::table('religions as rel')
            ->leftJoin('religion_translations as relt', function ($join) {
                $join->on('relt.religion_id', '=', 'rel.id')
                     ->where('relt.locale', '=', 'np');
            })
            ->leftJoin('house_members as hm', function($join) use ($selectedWard) {
                $join->on('hm.religion_id', '=', 'rel.id')
                    ->whereIn('hm.house_holder_id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                                $q->where('ward_id', $selectedWard);
                            });
                    });
            })
            ->select(
                'rel.id',
                DB::raw('COALESCE(MAX(relt.name), MAX(CAST(rel.id AS CHAR))) as label'),
                DB::raw('COUNT(hm.id) as total')
            )
            ->groupBy('rel.id')
            ->orderByDesc('total')
            ->get();

        $religionTotal = $religionStats->sum('total');

        $pinnedCharts = \App\Models\DashboardChart::where('user_id', auth()->id())->get();
        $chartsData = [];
        
        if ($pinnedCharts->isNotEmpty()) {
            foreach ($pinnedCharts as $pinned) {
                 $options = DB::table('question_options as qo')
                    ->join('option_choices as oc', 'oc.id', '=', 'qo.option_choice_id')
                    ->where('qo.question_id', $pinned->question_id)
                    ->select('qo.id', 'oc.choice_text', 'oc.custom_input_type')
                    ->get()
                    ->keyBy('id');
                    
                $answers = DB::table('answers as a')
                    ->join('responses as r', 'r.id', '=', 'a.response_id')
                    ->leftJoin('question_options as qo', 'qo.id', '=', 'a.question_option_id')
                    ->when($selectedWard !== 'all', function ($q) use ($selectedWard) {
                        $q->where('r.ward_id', $selectedWard);
                    })
                    ->where(function($query) use ($pinned) {
                        $query->where('a.question_id', $pinned->question_id)
                              ->orWhere('qo.question_id', $pinned->question_id);
                    })
                    ->select('a.question_option_id', 'a.custom_input_value')
                    ->get();
                    
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
                    $chartsData[$pinned->question_id] = [
                        'title' => $pinned->custom_title ?? DB::table('questions')->where('id', $pinned->question_id)->value('question_text'),
                        'labels' => array_keys($dataCounts),
                        'totals' => array_values($dataCounts),
                    ];
                }
            }
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
                'hm.full_name',
                'hm.age',
                'hm.dob_bs',
                DB::raw('COALESCE(MAX(gt.name), "—") as gender'),
                DB::raw('COALESCE(MAX(mst.name), "—") as marital_status'),
                DB::raw('COALESCE(MAX(elt.name), "—") as education_level'),
                DB::raw('COALESCE(MAX(hst.name), "—") as health_status'),
                DB::raw('COALESCE(MAX(itt.name), "—") as institution_type'),
                DB::raw('COALESCE(MAX(dt.name), "—") as disability')
            )
            ->groupBy(
                'hm.id', 'hm.full_name', 'hm.age', 'hm.dob_bs'
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
            ->select('q.id', 'q.question_text', 'q.order_index', 'it.input_type_name', 'q.scale_from', 'q.scale_to', 'q.scale_label_low', 'q.scale_label_high')
            ->orderBy('q.order_index')
            ->get();

        $charts = [];

        foreach ($questions as $question) {
            $dataCounts = [];
            $labels = [];
            $totals = [];
            $chartType = $question->input_type_name === 'linear_scale' ? 'bar' : 'pie';

            if ($question->input_type_name === 'linear_scale') {
                $from = $question->scale_from ?? 1;
                $to = $question->scale_to ?? 5;
                
                for ($i = $from; $i <= $to; $i++) {
                    $dataCounts[$i] = 0;
                }

                $answers = DB::table('answers as a')
                    ->join('responses as r', 'r.id', '=', 'a.response_id')
                    ->where('r.ward_id', $selectedWard)
                    ->where('a.question_id', $question->id)
                    ->whereNotNull('a.answer_numeric')
                    ->select('a.answer_numeric')
                    ->get();

                foreach ($answers as $answer) {
                    $val = (int)$answer->answer_numeric;
                    if (isset($dataCounts[$val])) {
                        $dataCounts[$val]++;
                    }
                }

                $labels = array_map('strval', array_keys($dataCounts));
                $totals = array_values($dataCounts);
            } else {
                $options = DB::table('question_options as qo')
                    ->join('option_choices as oc', 'oc.id', '=', 'qo.option_choice_id')
                    ->where('qo.question_id', $question->id)
                    ->select('qo.id', 'oc.choice_text', 'oc.custom_input_type')
                    ->get()
                    ->keyBy('id');

                $answers = DB::table('answers as a')
                    ->join('responses as r', 'r.id', '=', 'a.response_id')
                    ->leftJoin('question_options as qo', 'qo.id', '=', 'a.question_option_id')
                    ->where('r.ward_id', $selectedWard)
                    ->where(function($query) use ($question) {
                        $query->where('a.question_id', $question->id)
                              ->orWhere('qo.question_id', $question->id);
                    })
                    ->select('a.question_option_id', 'a.custom_input_value')
                    ->get();

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
                $charts[$question->id] = [
                    'question_text' => $question->question_text,
                    'labels' => $labels,
                    'totals' => $totals,
                    'chart_type' => $chartType,
                    'scale_label_low' => $question->scale_label_low ?? null,
                    'scale_label_high' => $question->scale_label_high ?? null,
                ];
            }
        }
        
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

        // --- Data Fetching Logic (Simplified copy from index) ---
        
        // Age Stats
        $ageStats = DB::table('house_members as hm')
            ->join('house_holders as hh', 'hh.id', '=', 'hm.house_holder_id')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($selectedWard !== 'all', fn($q) => $q->where('r.ward_id', $selectedWard))
            ->selectRaw("
                COUNT(CASE WHEN hm.age BETWEEN 0 AND 5 THEN 1 END) as group1,
                COUNT(CASE WHEN hm.age BETWEEN 6 AND 16 THEN 1 END) as group2,
                COUNT(CASE WHEN hm.age BETWEEN 17 AND 32 THEN 1 END) as group3,
                COUNT(CASE WHEN hm.age BETWEEN 33 AND 54 THEN 1 END) as group4,
                COUNT(CASE WHEN hm.age BETWEEN 55 AND 65 THEN 1 END) as group5,
                COUNT(CASE WHEN hm.age > 65 THEN 1 END) as group6,
                COUNT(hm.id) as total_members
            ")
            ->first();

        // Gender Stats
        $genderStats = DB::table('house_members as hm')
            ->join('house_holders as hh', 'hh.id', '=', 'hm.house_holder_id')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($selectedWard !== 'all', fn($q) => $q->where('r.ward_id', $selectedWard))
            ->selectRaw("
                COUNT(CASE WHEN hm.gender_id = 1 THEN 1 END) as male,
                COUNT(CASE WHEN hm.gender_id = 2 THEN 1 END) as female,
                COUNT(CASE WHEN hm.gender_id NOT IN (1, 2) THEN 1 END) as other,
                COUNT(hm.id) as total_members
            ")
            ->first();

        // Citizenship Stats
        $citStats = DB::table('house_holders as hh')
            ->join('responses as r', 'r.householder_id', '=', 'hh.id')
            ->when($selectedWard !== 'all', fn($q) => $q->where('r.ward_id', $selectedWard))
            ->selectRaw("
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 1 THEN 1 END) as stat1,
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 2 THEN 1 END) as stat2,
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 3 THEN 1 END) as stat3,
                COUNT(CASE WHEN hh.citizenship_permanent_address_id = 4 THEN 1 END) as stat4,
                COUNT(hh.id) as total_householders
            ")
            ->first();

        // Mother Tongue Stats
        $motherTongueStats = DB::table('mother_tongues as mt')
            ->leftJoin('house_holders as hh', function($join) use ($selectedWard) {
                $join->on('hh.mother_tongue_id', '=', 'mt.id')
                    ->whereIn('hh.id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', fn($q) => $q->where('ward_id', $selectedWard));
                    });
            })
            ->leftJoin('house_members as hm', 'hm.house_holder_id', '=', 'hh.id')
            ->select('mt.name', DB::raw('COUNT(hm.id) as total'))
            ->groupBy('mt.id', 'mt.name')
            ->orderByDesc('total')
            ->get();

        // Caste Stats
        $casteStats = DB::table('castes as c')
            ->leftJoin('house_holders as hh', function($join) use ($selectedWard) {
                $join->on('hh.caste_id', '=', 'c.id')
                    ->whereIn('hh.id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', fn($q) => $q->where('ward_id', $selectedWard));
                    });
            })
            ->leftJoin('house_members as hm', 'hm.house_holder_id', '=', 'hh.id')
            ->select('c.name', DB::raw('COUNT(hm.id) as total'))
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total')
            ->get();

        // Education Stats
        $educationStats = DB::table('education_levels as el')
            ->leftJoin('education_level_translations as elt', function ($join) {
                $join->on('elt.education_level_id', '=', 'el.id')
                     ->where('elt.locale', '=', 'np');
            })
            ->leftJoin('house_members as hm', function($join) use ($selectedWard) {
                $join->on('hm.education_level_id', '=', 'el.id')
                    ->whereIn('hm.house_holder_id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', fn($q) => $q->where('ward_id', $selectedWard));
                    });
            })
            ->select(
                DB::raw('COALESCE(MAX(elt.name), MAX(CAST(el.id AS CHAR))) as label'),
                DB::raw('COUNT(hm.id) as total')
            )
            ->groupBy('el.id')
            ->orderByDesc('total')
            ->get();

        // Religion Stats
        $religionStats = DB::table('religions as rel')
            ->leftJoin('religion_translations as relt', function ($join) {
                $join->on('relt.religion_id', '=', 'rel.id')
                     ->where('relt.locale', '=', 'np');
            })
            ->leftJoin('house_members as hm', function($join) use ($selectedWard) {
                $join->on('hm.religion_id', '=', 'rel.id')
                    ->whereIn('hm.house_holder_id', function($query) use ($selectedWard) {
                        $query->select('householder_id')
                            ->from('responses')
                            ->when($selectedWard !== 'all', fn($q) => $q->where('ward_id', $selectedWard));
                    });
            })
            ->select(
                DB::raw('COALESCE(MAX(relt.name), MAX(CAST(rel.id AS CHAR))) as label'),
                DB::raw('COUNT(hm.id) as total')
            )
            ->groupBy('rel.id')
            ->orderByDesc('total')
            ->get();

        // --- Prepare CSV ---
        $filename = "dashboard_data_ward_{$selectedWard}_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($ageStats, $genderStats, $citStats, $motherTongueStats, $casteStats, $educationStats, $religionStats, $wardNoDisplay) {
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
            $totalAge = $ageStats->total_members ?: 1;
            fputcsv($file, ['Age Group', 'Infant (0-5)', $ageStats->group1, round(($ageStats->group1 / $totalAge) * 100, 2)]);
            fputcsv($file, ['Age Group', 'Children (6-16)', $ageStats->group2, round(($ageStats->group2 / $totalAge) * 100, 2)]);
            fputcsv($file, ['Age Group', 'Youth (17-32)', $ageStats->group3, round(($ageStats->group3 / $totalAge) * 100, 2)]);
            fputcsv($file, ['Age Group', 'Adult (33-54)', $ageStats->group4, round(($ageStats->group4 / $totalAge) * 100, 2)]);
            fputcsv($file, ['Age Group', 'Elderly (55-65)', $ageStats->group5, round(($ageStats->group5 / $totalAge) * 100, 2)]);
            fputcsv($file, ['Age Group', 'Senior Citizen (65+)', $ageStats->group6, round(($ageStats->group6 / $totalAge) * 100, 2)]);

            fputcsv($file, []);

            // Gender
            $totalGender = $genderStats->total_members ?: 1;
            fputcsv($file, ['Gender', 'Male', $genderStats->male, round(($genderStats->male / $totalGender) * 100, 2)]);
            fputcsv($file, ['Gender', 'Female', $genderStats->female, round(($genderStats->female / $totalGender) * 100, 2)]);
            fputcsv($file, ['Gender', 'Other', $genderStats->other, round(($genderStats->other / $totalGender) * 100, 2)]);

            fputcsv($file, []);

            // Citizenship
            $totalCit = $citStats->total_householders ?: 1;
            fputcsv($file, ['Residence (Household)', 'स्थायी जन्म', $citStats->stat1, round(($citStats->stat1 / $totalCit) * 100, 2)]);
            fputcsv($file, ['Residence (Household)', 'बसाईसराई', $citStats->stat2, round(($citStats->stat2 / $totalCit) * 100, 2)]);
            fputcsv($file, ['Residence (Household)', 'अस्थायी बसोबास', $citStats->stat3, round(($citStats->stat3 / $totalCit) * 100, 2)]);
            fputcsv($file, ['Residence (Household)', 'बसाईसराईको निसा नभएको', $citStats->stat4, round(($citStats->stat4 / $totalCit) * 100, 2)]);

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