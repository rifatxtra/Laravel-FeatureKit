<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\User\UserDashboardService;

class DashboardController extends Controller
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
