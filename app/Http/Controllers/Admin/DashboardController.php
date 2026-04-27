<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;

class DashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService
    ) {}

    public function index()
    {
        return inertia('(portals)/admin/dashboard/page', [
            'metrics' => $this->dashboardService->getMetrics(),
            'recent_activity' => $this->dashboardService->getRecentActivity(10)
        ]);
    }
}
