<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DashboardStatistic;

class AggregateDashboardStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:aggregate-stats {--ward= : Only aggregate for this ward ID (or "all")}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-calculates dashboard statistics to optimize loading times.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $startTime = now();
            $wardOption = $this->option('ward');

            if ($wardOption !== null) {
                // Only aggregate for the specified ward (or 'all' aggregate)
                $targets = ($wardOption === 'all') ? ['all'] : ['all', $wardOption];
            } else {
                $wards = DB::table('wards')->pluck('id')->toArray();
                $targets = array_merge(['all'], $wards);
            }

            $this->info(\sprintf('[%s] Starting dashboard stats aggregation for %d targets', $startTime->format('Y-m-d H:i:s'), count($targets)));

        foreach ($targets as $selectedWard) {
            $this->info("Aggregating stats for Ward: {$selectedWard}");
            
            // Age Stats
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

            // Gender Stats
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

            // Citizenship Stats
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

            $totalHouseholders = $citStats->total_householders;

            $citizenshipGroups = [
                [
                    'id' => 1,
                    'label' => 'स्थायी जन्म',
                    'count' => $citStats->stat1,
                    'percentage' => $totalHouseholders > 0 ? number_format(($citStats->stat1 / $totalHouseholders) * 100, 2) : 0,
                    'color' => 'bg-teal-500',
                    'light_color' => 'bg-teal-50',
                    'border_color' => 'border-teal-200'
                ],
                [
                    'id' => 2,
                    'label' => 'बसाईसराई',
                    'count' => $citStats->stat2,
                    'percentage' => $totalHouseholders > 0 ? number_format(($citStats->stat2 / $totalHouseholders) * 100, 2) : 0,
                    'color' => 'bg-indigo-500',
                    'light_color' => 'bg-indigo-50',
                    'border_color' => 'border-indigo-200'
                ],
                [
                    'id' => 3,
                    'label' => 'अस्थायी बसोबास',
                    'count' => $citStats->stat3,
                    'percentage' => $totalHouseholders > 0 ? number_format(($citStats->stat3 / $totalHouseholders) * 100, 2) : 0,
                    'color' => 'bg-fuchsia-500',
                    'light_color' => 'bg-fuchsia-50',
                    'border_color' => 'border-fuchsia-200'
                ],
                [
                    'id' => 4,
                    'label' => 'बसाईसराईको निसा नभएको',
                    'count' => $citStats->stat4,
                    'percentage' => $totalHouseholders > 0 ? number_format(($citStats->stat4 / $totalHouseholders) * 100, 2) : 0,
                    'color' => 'bg-slate-500',
                    'light_color' => 'bg-slate-50',
                    'border_color' => 'border-slate-200'
                ],
            ];

            // Mother Tongue Stats
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

            // Caste Stats
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

            // Upsert into summary table
            DashboardStatistic::updateOrCreate(
                ['ward_id' => (string)$selectedWard],
                [
                    'age_groups' => $ageGroups,
                    'gender_groups' => $genderGroups,
                    'citizenship_groups' => $citizenshipGroups,
                    'mother_tongue_stats' => $motherTongueStats->toArray(),
                    'caste_stats' => $casteStats->toArray(),
                    'education_stats' => $educationStats->toArray(),
                    'religion_stats' => $religionStats->toArray(),
                    'total_householders' => $totalHouseholders,
                    'total_members' => $ageStats->total_members ?? 0,
                ]
            );

            $this->info(\sprintf('  ✓ Aggregated: Ward %s', $selectedWard));
        }

        // Invalidate all dashboard caches for fresh data display
        $this->invalidateDashboardCaches($targets);

        $duration = now()->diffInSeconds($startTime);
        $this->info(\sprintf('[%s] ✓ Dashboard stats aggregated successfully in %d seconds', now()->format('Y-m-d H:i:s'), $duration));

        } catch (\Exception $e) {
            $this->error(\sprintf('[%s] ✗ Error during aggregation: %s', now()->format('Y-m-d H:i:s'), $e->getMessage()));
            \Illuminate\Support\Facades\Log::error('Dashboard aggregation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    /**
     * Invalidate dashboard caches for all wards
     */
    private function invalidateDashboardCaches(array $wards): void
    {
        $cache = \Illuminate\Support\Facades\Cache::class;
        
        foreach ($wards as $ward) {
            $cacheKey = "dashboard_charts_{$ward}";
            $pinnedCacheKey = "dashboard_pinned_charts_{$ward}";
            
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
            \Illuminate\Support\Facades\Cache::forget($pinnedCacheKey);
            $this->info(\sprintf('  ✓ Cache cleared: %s', $cacheKey));
        }
    }
}
