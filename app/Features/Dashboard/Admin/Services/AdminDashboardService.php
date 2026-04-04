<?php

namespace App\Features\Dashboard\Admin\Services;

use App\Core\BaseService;
use App\Features\Auth\Models\User;
use App\Features\ActivityLog\Models\ActivityLog;

class AdminDashboardService extends BaseService
{
    /**
     * Get primary dashboard metrics.
     */
    public function getMetrics(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'suspended_users' => User::where('is_active', false)->count(),
            'total_activities' => ActivityLog::count(),
        ];
    }

    /**
     * Get recent system-wide activity logs.
     */
    public function getRecentActivity(int $limit = 10)
    {
        return ActivityLog::with('user:id,name,email,profile_image')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
