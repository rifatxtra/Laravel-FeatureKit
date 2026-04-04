<?php

namespace App\Features\Dashboard\User\Controllers;

use App\Core\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Features\Dashboard\User\Services\UserDashboardService;

class DashboardController extends BaseController
{
    public function __construct(
        protected UserDashboardService $dashboardService
    ) {}

    public function index()
    {
        $user = Auth::user();

        return inertia('(portals)/user/dashboard/page', [
            'user' => $user,
            'stats' => $this->dashboardService->getUserStats($user),
            'recent_activity' => $this->dashboardService->getRecentActivity($user, 10)
        ])->withViewData([
            'title' => 'User Dashboard'
        ]);
    }
}
