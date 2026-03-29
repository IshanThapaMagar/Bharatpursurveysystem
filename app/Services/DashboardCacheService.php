<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardCacheService
{
    /**
     * @param  int|string|null  $wardId  Specific ward ID, or null to flush everything.
     */
    public static function invalidate($wardId = null): void
    {
        $targets = ['all'];

        if ($wardId !== null) {
            $targets[] = (string) $wardId;
        } else {
            // No ward specified – flush for every ward
            $wards = DB::table('wards')->pluck('id')->toArray();
            foreach ($wards as $id) {
                $targets[] = (string) $id;
            }
        }

        foreach ($targets as $ward) {
            // Main dashboard charts (index page)
            Cache::forget("dashboard_charts_{$ward}");

            // Survey report charts
            Cache::forget("survey_report_charts_{$ward}");
        }

        $pinnedKeys = DB::table('dashboard_charts')
            ->join('users', 'users.id', '=', 'dashboard_charts.user_id')
            ->select('dashboard_charts.user_id', 'users.ward_id as user_ward_id')
            ->distinct()
            ->get();

        foreach ($pinnedKeys as $row) {
            Cache::forget("dashboard_pinned_charts_all_{$row->user_id}");

            if ($wardId !== null) {
                Cache::forget("dashboard_pinned_charts_{$wardId}_{$row->user_id}");
            } else {
                foreach ($targets as $ward) {
                    Cache::forget("dashboard_pinned_charts_{$ward}_{$row->user_id}");
                }
            }
        }
    }
}
