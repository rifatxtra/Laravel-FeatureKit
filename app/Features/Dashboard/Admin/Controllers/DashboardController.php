<?php

namespace App\Features\Dashboard\Admin\Controllers;

use App\Core\BaseController;
use App\Features\Dashboard\Admin\Services\AdminDashboardService;

class DashboardController extends BaseController
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
