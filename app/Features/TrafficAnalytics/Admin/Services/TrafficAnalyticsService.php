<?php

namespace App\Features\TrafficAnalytics\Admin\Services;

use App\Core\BaseService;
use App\Features\TrafficAnalytics\Models\TrafficLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrafficAnalyticsService extends BaseService
{
    /**
     * Get comprehensive statistics for the Traffic Analytics dashboard.
     *
     * @param array $filters ['days', 'uri', 'is_bot']
     */
    public function getDashboardStats(array $filters = []): array
    {
        $days        = (int) ($filters['days'] ?? 30);
        $uri         = $filters['uri'] ?? null;
        $includeBots = filter_var($filters['is_bot'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $query = TrafficLog::query();

        // Date filter
        if ($days > 0) {
            $query->where('created_at', '>=', Carbon::now()->subDays($days));
        }

        // URI filter
        if ($uri) {
            $query->where('uri', 'like', "%{$uri}%");
        }

        // Bot filter
        if (! $includeBots) {
            $query->where('is_bot', false);
        }

        // Base clone for all aggregations
        $q = clone $query;

        // ── 1. Visits Over Time ─────────────────────────────────────────────
        $visitsOverTime = (clone $q)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_visits'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_visits'),
                DB::raw('COUNT(DISTINCT session_id) as sessions')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ── 2. Device Distribution ──────────────────────────────────────────
        $deviceDistribution = (clone $q)
            ->select('device_type', DB::raw('COUNT(*) as count'))
            ->groupBy('device_type')
            ->get();

        // ── 3. Browser Distribution ─────────────────────────────────────────
        $browserDistribution = (clone $q)
            ->select('browser', DB::raw('COUNT(*) as count'))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // ── 4. OS Distribution ──────────────────────────────────────────────
        $osDistribution = (clone $q)
            ->select('os', DB::raw('COUNT(*) as count'))
            ->groupBy('os')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // ── 5. Top Pages ────────────────────────────────────────────────────
        $topPages = (clone $q)
            ->select('uri', DB::raw('COUNT(*) as count'))
            ->groupBy('uri')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // ── 6. Referrer Sources ─────────────────────────────────────────────
        $rawReferrers = (clone $q)
            ->select('referrer', DB::raw('COUNT(*) as count'))
            ->whereNotNull('referrer')
            ->groupBy('referrer')
            ->get();

        $referrerSources = $this->categoriseReferrers($rawReferrers);

        // ── 7. Hourly Activity Heatmap ──────────────────────────────────────
        // Returns [dayOfWeek][hour] = count  (0=Sunday … 6=Saturday)
        $heatmapRaw = (clone $q)
            ->select(
                DB::raw('DAYOFWEEK(created_at) - 1 as day_of_week'),
                DB::raw('HOUR(created_at) as hour_of_day'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('day_of_week', 'hour_of_day')
            ->get();

        $heatmap = $this->buildHeatmap($heatmapRaw);

        // ── 8. Status Code Distribution ─────────────────────────────────────
        $statusCodes = (clone $q)
            ->select(
                DB::raw("CONCAT(FLOOR(status_code / 100), 'xx') as group_code"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('group_code')
            ->orderBy('group_code')
            ->get();

        // ── 9. Geographic Breakdown ─────────────────────────────────────────
        $geoBreakdown = (clone $q)
            ->select('country_code', 'country_name', DB::raw('COUNT(*) as count'))
            ->whereNotNull('country_code')
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('count')
            ->limit(15)
            ->get();

        // ── 10. New vs Returning Visitors ────────────────────────────────────
        $newVisitors       = (clone $q)->where('is_new_visitor', true)->distinct('ip_address')->count();
        $returningVisitors = (clone $q)->where('is_new_visitor', false)->distinct('ip_address')->count();

        // ── 11. Session Metrics ──────────────────────────────────────────────
        $sessionMetrics = $this->getSessionMetrics(clone $q);

        // ── 12. Top Entry Pages ──────────────────────────────────────────────
        $topEntryPages = (clone $q)
            ->select('uri', DB::raw('COUNT(DISTINCT session_id) as sessions'))
            ->groupBy('uri')
            ->orderByDesc('sessions')
            ->limit(5)
            ->get();

        // ── 13. Average Response Time ────────────────────────────────────────
        $avgResponseTime = round((clone $q)->whereNotNull('response_time')->avg('response_time') ?? 0, 2);

        // ── 14. Response Time Trend ──────────────────────────────────────────
        $responseTimeTrend = (clone $q)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('ROUND(AVG(response_time), 2) as avg_ms'),
                DB::raw('ROUND(MAX(response_time), 2) as max_ms')
            )
            ->whereNotNull('response_time')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ── 15. Summary Metrics ──────────────────────────────────────────────
        $totalViews   = (clone $q)->count();
        $uniqueIPs    = (clone $q)->distinct('ip_address')->count();
        $totalSessions = (clone $q)->whereNotNull('session_id')->distinct('session_id')->count();

        $summary = [
            'total_page_views'    => $totalViews,
            'unique_visitors'     => $uniqueIPs,
            'avg_daily_views'     => $days > 0 ? round($totalViews / max($days, 1), 1) : $totalViews,
            'bot_traffic'         => (clone $query)->where('is_bot', true)->count(),
            'new_visitors'        => $newVisitors,
            'returning_visitors'  => $returningVisitors,
            'total_sessions'      => $totalSessions,
            'avg_response_time'   => $avgResponseTime,
            'errors_4xx'          => (clone $q)->whereBetween('status_code', [400, 499])->count(),
            'errors_5xx'          => (clone $q)->whereBetween('status_code', [500, 599])->count(),
            'bounce_rate'         => $sessionMetrics['bounce_rate'],
            'avg_pages_per_session' => $sessionMetrics['avg_pages_per_session'],
        ];

        // ── 16. Recent Logs ──────────────────────────────────────────────────
        $recentLogs = (clone $q)->with('user')->latest()->limit(20)->get();

        return [
            'visits_over_time'       => $visitsOverTime,
            'device_distribution'    => $deviceDistribution,
            'browser_distribution'   => $browserDistribution,
            'os_distribution'        => $osDistribution,
            'top_pages'              => $topPages,
            'referrer_sources'       => $referrerSources,
            'heatmap'                => $heatmap,
            'status_codes'           => $statusCodes,
            'geo_breakdown'          => $geoBreakdown,
            'top_entry_pages'        => $topEntryPages,
            'response_time_trend'    => $responseTimeTrend,
            'recent_logs'            => $recentLogs,
            'summary'                => $summary,
            'filters'                => [
                'days'   => $days,
                'uri'    => $uri,
                'is_bot' => $includeBots,
            ],
        ];
    }

    /**
     * Get real-time / live analytics stats (last N minutes).
     * Designed to be called via a lightweight REST poll every 10–30s.
     */
    public function getRealTimeStats(int $minutes = 5): array
    {
        $since  = Carbon::now()->subMinutes($minutes);
        $since30 = Carbon::now()->subMinutes(30);

        // Active visitors in the last $minutes
        $activeVisitors = TrafficLog::where('created_at', '>=', $since)
            ->where('is_bot', false)
            ->distinct('ip_address')
            ->count();

        // Pages being viewed right now
        $activePages = TrafficLog::where('created_at', '>=', $since)
            ->where('is_bot', false)
            ->select('uri', DB::raw('COUNT(*) as hits'), DB::raw('MAX(created_at) as last_seen'))
            ->groupBy('uri')
            ->orderByDesc('hits')
            ->limit(10)
            ->get();

        // Last 30 minutes traffic per minute (for sparkline)
        $perMinute = TrafficLog::where('created_at', '>=', $since30)
            ->where('is_bot', false)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%H:%i") as minute'),
                DB::raw('COUNT(*) as hits')
            )
            ->groupBy('minute')
            ->orderBy('minute')
            ->get();

        // Recent hits stream
        $recentHits = TrafficLog::where('is_bot', false)
            ->with('user')
            ->latest()
            ->limit(15)
            ->get(['id', 'uri', 'ip_address', 'country_code', 'country_name', 'browser', 'device_type', 'created_at', 'user_id', 'status_code']);

        // Active countries
        $activeCountries = TrafficLog::where('created_at', '>=', $since)
            ->where('is_bot', false)
            ->whereNotNull('country_code')
            ->select('country_code', 'country_name', DB::raw('COUNT(*) as count'))
            ->groupBy('country_code', 'country_name')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // Requests per second avg over the last minute
        $lastMinuteCount = TrafficLog::where('created_at', '>=', Carbon::now()->subMinute())
            ->where('is_bot', false)
            ->count();
        $reqPerSecond = round($lastMinuteCount / 60, 2);

        return [
            'active_visitors'  => $activeVisitors,
            'active_pages'     => $activePages,
            'per_minute'       => $perMinute,
            'recent_hits'      => $recentHits,
            'active_countries' => $activeCountries,
            'req_per_second'   => $reqPerSecond,
            'window_minutes'   => $minutes,
            'generated_at'     => now()->toISOString(),
        ];
    }

    /**
     * Get paginated logs for the dedicated logs viewer.
     *
     * @param array $filters ['days', 'uri', 'is_bot', 'browser', 'os', 'device_type', 'status_code', 'country_code']
     */
    public function getPaginatedLogs(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $days        = (int) ($filters['days'] ?? 0);
        $uri         = $filters['uri'] ?? null;
        $includeBots = filter_var($filters['is_bot'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $browser     = $filters['browser'] ?? null;
        $os          = $filters['os'] ?? null;
        $deviceType  = $filters['device_type'] ?? null;
        $statusCode  = $filters['status_code'] ?? null;
        $countryCode = $filters['country_code'] ?? null;

        $query = TrafficLog::query()->with('user');

        if ($days > 0) {
            $query->where('created_at', '>=', Carbon::now()->subDays($days));
        }
        if ($uri)         { $query->where('uri', 'like', "%{$uri}%"); }
        if (! $includeBots) { $query->where('is_bot', false); }
        if ($browser)     { $query->where('browser', $browser); }
        if ($os)          { $query->where('os', $os); }
        if ($deviceType)  { $query->where('device_type', $deviceType); }
        if ($statusCode)  { $query->where('status_code', (int) $statusCode); }
        if ($countryCode) { $query->where('country_code', $countryCode); }

        return $query->latest()->paginate(50)->withQueryString();
    }

    // ── Private helpers ─────────────────────────────────────────────────────

    /**
     * Categorise referrer URLs into groups: Direct, Search, Social, Other.
     */
    private function categoriseReferrers($rawReferrers): array
    {
        $search = ['google.', 'bing.', 'yahoo.', 'duckduckgo.', 'baidu.', 'yandex.'];
        $social = ['facebook.', 'twitter.', 'x.com', 'instagram.', 'linkedin.', 't.co', 'youtube.', 'tiktok.', 'reddit.', 'pinterest.'];

        $categories = ['Direct' => 0, 'Search' => 0, 'Social' => 0, 'Other' => 0];

        foreach ($rawReferrers as $row) {
            $ref   = strtolower($row->referrer ?? '');
            $count = (int) $row->count;
            $matched = false;

            foreach ($search as $s) {
                if (str_contains($ref, $s)) {
                    $categories['Search'] += $count;
                    $matched = true;
                    break;
                }
            }
            if (! $matched) {
                foreach ($social as $s) {
                    if (str_contains($ref, $s)) {
                        $categories['Social'] += $count;
                        $matched = true;
                        break;
                    }
                }
            }
            if (! $matched) {
                $categories['Other'] += $count;
            }
        }

        // Direct (no referrer) - we count separately
        $categories['Direct'] = TrafficLog::whereNull('referrer')->orWhere('referrer', '')->count();

        return collect($categories)->map(fn($count, $name) => [
            'name'  => $name,
            'count' => $count,
        ])->values()->toArray();
    }

    /**
     * Build a 7×24 heatmap grid from raw data.
     * Returns array indexed [0..6][0..23] = hit count.
     */
    private function buildHeatmap($raw): array
    {
        // Init 7-day × 24-hour grid
        $grid = [];
        for ($d = 0; $d < 7; $d++) {
            for ($h = 0; $h < 24; $h++) {
                $grid[$d][$h] = 0;
            }
        }

        foreach ($raw as $row) {
            $d = (int) $row->day_of_week;
            $h = (int) $row->hour_of_day;
            if (isset($grid[$d][$h])) {
                $grid[$d][$h] = (int) $row->count;
            }
        }

        return $grid;
    }

    /**
     * Calculate session-based metrics: bounce rate and avg pages/session.
     */
    private function getSessionMetrics($query): array
    {
        // Get page counts per session
        $sessionCounts = (clone $query)
            ->whereNotNull('session_id')
            ->select('session_id', DB::raw('COUNT(*) as page_count'))
            ->groupBy('session_id')
            ->get();

        if ($sessionCounts->isEmpty()) {
            return ['bounce_rate' => 0, 'avg_pages_per_session' => 0];
        }

        $total      = $sessionCounts->count();
        $bounces    = $sessionCounts->where('page_count', 1)->count();
        $avgPages   = round($sessionCounts->avg('page_count'), 1);
        $bounceRate = round(($bounces / max($total, 1)) * 100, 1);

        return [
            'bounce_rate'           => $bounceRate,
            'avg_pages_per_session' => $avgPages,
        ];
    }
}
