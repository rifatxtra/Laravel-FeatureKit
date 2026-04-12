<?php

namespace App\Features\TrafficAnalytics\Console\Commands;

use App\Features\TrafficAnalytics\Models\TrafficLog;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupTrafficLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-traffic {days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune old traffic logs from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->argument('days') ?: 30;
        $date = Carbon::now()->subDays($days);

        $count = TrafficLog::where('created_at', '<', $date)->delete();

        $this->info("Successfully pruned {$count} traffic logs older than {$days} days.");
    }
}
