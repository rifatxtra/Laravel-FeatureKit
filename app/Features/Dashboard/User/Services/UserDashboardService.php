<?php

namespace App\Features\Dashboard\User\Services;

use App\Core\BaseService;
use App\Features\Auth\Models\User;
use App\Features\ActivityLog\Models\ActivityLog;

class UserDashboardService extends BaseService
{
    /**
     * Get user-specific dashboard metrics.
     */
    public function getUserStats(User $user): array
    {
        // Calculate profile completion
        $fields = ['name', 'email', 'phone', 'profile_image'];
        $filled = 0;
        foreach ($fields as $field) {
            if (!empty($user->{$field})) {
                $filled++;
            }
        }
        $profileCompletion = round(($filled / count($fields)) * 100);

        return [
            'profile_completion' => $profileCompletion,
            'member_since' => $user->created_at->diffForHumans(),
            'total_actions' => ActivityLog::where('user_id', $user->id)->count(),
        ];
    }

    /**
     * Get recent personal activity logs.
     */
    public function getRecentActivity(User $user, int $limit = 10)
    {
        return ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
