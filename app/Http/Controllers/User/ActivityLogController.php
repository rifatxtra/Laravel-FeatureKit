<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * User sees only their own activity logs.
     */
    public function index()
    {
        $logs = ActivityLog::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return inertia('(portals)/user/activity-logs/page', [
            'logs' => $logs,
        ]);
    }
}
