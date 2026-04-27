<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Admin sees ALL users' activity logs.
     */
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return inertia('(portals)/admin/activity-logs/page', [
            'logs' => $logs,
        ]);
    }
}
