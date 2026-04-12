<?php

namespace App\Features\TrafficAnalytics\Admin\Controllers;

use App\Core\BaseController;
use App\Features\TrafficAnalytics\Admin\Services\TrafficAnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrafficController extends BaseController
{
    protected TrafficAnalyticsService $trafficService;

    public function __construct(TrafficAnalyticsService $trafficService)
    {
        $this->trafficService = $trafficService;
    }

    /**
     * Display the Traffic Analytics dashboard.
     */
    public function index(Request $request)
    {
        $stats = $this->trafficService->getDashboardStats($request->all());

        return Inertia::render('(portals)/admin/traffic/page', [
            'stats' => $stats,
        ]);
    }

    /**
     * REST endpoint: real-time visitor stats (poll every 10–30s via fetch).
     */
    public function realtime(Request $request)
    {
        $minutes = min((int) ($request->get('minutes', 5)), 60);
        $data    = $this->trafficService->getRealTimeStats($minutes);

        return response()->json($data);
    }

    /**
     * Display the dedicated Traffic Logs viewer.
     */
    public function logs(Request $request)
    {
        $logs = $this->trafficService->getPaginatedLogs($request->all());

        return Inertia::render('(portals)/admin/traffic/logs/page', [
            'logs'    => $logs,
            'filters' => $request->only(['days', 'uri', 'is_bot', 'browser', 'os', 'device_type', 'status_code', 'country_code']),
        ]);
    }
}
