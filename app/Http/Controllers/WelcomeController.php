<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {

        if (!session()->has('locale')) {
            app()->setLocale('np');
        }

        $wards = DB::table('wards')->orderBy('ward_no')->get();
        $selectedWard = $request->input('ward', 'all');

        // Validate ward selection
        if ($selectedWard !== 'all' && !$wards->pluck('id')->contains((int) $selectedWard)) {
            $selectedWard = 'all';
        }

        // Age stats
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
                'label' => __('Infant'),
                'range' => '0-5',
                'count' => $ageStats->group1,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group1 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-rose-500',
                'light_color' => 'bg-rose-100',
                'border_color' => 'border-rose-200',
            ],
            [
                'label' => __('Children'),
                'range' => '6-16',
                'count' => $ageStats->group2,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group2 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-blue-500',
                'light_color' => 'bg-blue-100',
                'border_color' => 'border-blue-200',
            ],
            [
                'label' => __('Youth'),
                'range' => '17-32',
                'count' => $ageStats->group3,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group3 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-amber-500',
                'light_color' => 'bg-amber-100',
                'border_color' => 'border-amber-200',
            ],
            [
                'label' => __('Adult'),
                'range' => '33-54',
                'count' => $ageStats->group4,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group4 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-emerald-500',
                'light_color' => 'bg-emerald-100',
                'border_color' => 'border-emerald-200',
            ],
            [
                'label' => __('Elderly'),
                'range' => '55-65',
                'count' => $ageStats->group5,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group5 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-violet-500',
                'light_color' => 'bg-violet-100',
                'border_color' => 'border-violet-200',
            ],
            [
                'label' => __('Senior Citizen'),
                'range' => '65+',
                'count' => $ageStats->group6,
                'percentage' => $ageStats->total_members > 0 ? round(($ageStats->group6 / $ageStats->total_members) * 100, 2) : 0,
                'color' => 'bg-cyan-500',
                'light_color' => 'bg-cyan-100',
                'border_color' => 'border-cyan-200',
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
                'border_color' => 'border-blue-200',
            ],
            [
                'label' => __('Female'),
                'count' => $genderStats->female,
                'percentage' => $genderStats->total_members > 0 ? round(($genderStats->female / $genderStats->total_members) * 100, 2) : 0,
                'color' => 'bg-pink-600',
                'light_color' => 'bg-pink-50',
                'border_color' => 'border-pink-200',
            ],
            [
                'label' => __('Other/LGBTQ+'),
                'count' => $genderStats->other,
                'percentage' => $genderStats->total_members > 0 ? round(($genderStats->other / $genderStats->total_members) * 100, 2) : 0,
                'color' => 'bg-orange-600',
                'light_color' => 'bg-orange-50',
                'border_color' => 'border-orange-200',
            ],
        ];

        // Citizenship/Residence stats
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
            ['id' => 1, 'label' => 'स्थायी जन्म', 'count' => $citStats->stat1, 'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat1 / $citStats->total_householders) * 100, 2) : 0, 'color' => 'bg-teal-500'],
            ['id' => 2, 'label' => 'बसाईसराई', 'count' => $citStats->stat2, 'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat2 / $citStats->total_householders) * 100, 2) : 0, 'color' => 'bg-indigo-500'],
            ['id' => 3, 'label' => 'अस्थायी बसोबास', 'count' => $citStats->stat3, 'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat3 / $citStats->total_householders) * 100, 2) : 0, 'color' => 'bg-fuchsia-500'],
            ['id' => 4, 'label' => 'बसाईसराईको निसा नभएको', 'count' => $citStats->stat4, 'percentage' => $citStats->total_householders > 0 ? number_format(($citStats->stat4 / $citStats->total_householders) * 100, 2) : 0, 'color' => 'bg-slate-500'],
        ];

        // Mother tongue stats
        $motherTongueStats = DB::table('mother_tongues as mt')
            ->leftJoin('house_holders as hh', function ($join) use ($selectedWard) {
                $join->on('hh.mother_tongue_id', '=', 'mt.id')
                    ->whereIn('hh.id', function ($query) use ($selectedWard) {
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

        // Caste stats
        $casteStats = DB::table('castes as c')
            ->leftJoin('house_holders as hh', function ($join) use ($selectedWard) {
                $join->on('hh.caste_id', '=', 'c.id')
                    ->whereIn('hh.id', function ($query) use ($selectedWard) {
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

        // Education stats
        $educationStats = DB::table('education_levels as el')
            ->leftJoin('education_level_translations as elt', function ($join) {
                $join->on('elt.education_level_id', '=', 'el.id')
                    ->where('elt.locale', '=', 'np');
            })
            ->leftJoin('house_members as hm', function ($join) use ($selectedWard) {
                $join->on('hm.education_level_id', '=', 'el.id')
                    ->whereIn('hm.house_holder_id', function ($query) use ($selectedWard) {
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

        // Religion stats
        $religionStats = DB::table('religions as rel')
            ->leftJoin('religion_translations as relt', function ($join) {
                $join->on('relt.religion_id', '=', 'rel.id')
                    ->where('relt.locale', '=', 'np');
            })
            ->leftJoin('house_members as hm', function ($join) use ($selectedWard) {
                $join->on('hm.religion_id', '=', 'rel.id')
                    ->whereIn('hm.house_holder_id', function ($query) use ($selectedWard) {
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

        // Total population summary
        $totalPopulation = $ageStats->total_members ?? 0;
        $totalHouseholds = $citStats->total_householders ?? 0;

        // Pinned survey analytics — show all pinned charts publicly (across all users)
        $pinnedCharts = \App\Models\DashboardChart::all();
        $chartsData = [];

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
                ->where(function ($query) use ($pinned) {
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
                    if (!empty(trim((string) $answer->custom_input_value))) {
                        $label .= ' (' . trim((string) $answer->custom_input_value) . ')';
                    }
                } elseif (!empty(trim((string) $answer->custom_input_value))) {
                    $label = trim((string) $answer->custom_input_value);
                }

                if (empty($label))
                    continue;

                if (!isset($dataCounts[$label]))
                    $dataCounts[$label] = 0;
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

        return view('welcome', compact(
            'wards',
            'selectedWard',
            'ageGroups',
            'genderGroups',
            'citizenshipGroups',
            'motherTongueStats',
            'motherTongueTotal',
            'casteStats',
            'casteTotal',
            'educationStats',
            'educationTotal',
            'religionStats',
            'religionTotal',
            'totalPopulation',
            'totalHouseholds',
            'chartsData'
        ));
    }
}