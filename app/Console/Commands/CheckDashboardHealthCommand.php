<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\DashboardStatistic;

class CheckDashboardHealthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dashboard:health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if dashboard statistics are fresh and up-to-date.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('[Dashboard Health Check] Starting...');
            
            $stats = DashboardStatistic::all();

            if ($stats->isEmpty()) {
                $this->warn('[WARNING] No dashboard statistics found. Run: php artisan dashboard:aggregate-stats');
                return 1;
            }

            $freshCount = 0;
            $staleCount = 0;
            $threshold = now()->subMinutes(15); // Alert if older than 15 minutes

            foreach ($stats as $stat) {
                $isFresh = $stat->updated_at->isAfter($threshold);
                $age = $stat->updated_at->diffInMinutes(now());

                $status = $isFresh ? '✓' : '✗';
                $wardInfo = $stat->ward_id === 'all' ? 'All Wards' : "Ward {$stat->ward_id}";
                
                $this->line(\sprintf(
                    '%s %s: %d members, %d householders (Last updated: %d min ago)',
                    $status,
                    $wardInfo,
                    $stat->total_members ?? 0,
                    $stat->total_householders ?? 0,
                    $age
                ));

                if ($isFresh) $freshCount++;
                else $staleCount++;
            }

            $this->newLine();
            $this->info(\sprintf('Fresh: %d | Stale (>15min): %d', $freshCount, $staleCount));

            if ($staleCount > 0) {
                $this->warn('[WARNING] Some statistics are stale. Run: php artisan dashboard:aggregate-stats');
                return 1;
            }

            $this->info('✓ Dashboard health check passed!');
            return 0;

        } catch (\Exception $e) {
            $this->error(\sprintf('[ERROR] Health check failed: %s', $e->getMessage()));
            \Illuminate\Support\Facades\Log::error('Dashboard health check error', [
                'error' => $e->getMessage(),
            ]);
            return 1;
        }
    }
}