<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        return inertia('(portals)/user/notification/page', [
            'notifications' => Notification::where('user_id', Auth::id())->latest()->paginate(10),
        ]);
    }

    public function readAll()
    {
        Notification::where('user_id', Auth::id())->update(['read_at' => now()]);
        return back();
    }

    public function read($id)
    {
        $notification = Notification::where('user_id', Auth::id())->find($id);
        if ($notification) {
            $notification->update(['read_at' => now()]);
        }
        return inertia('(portals)/user/notification/[id]/page', [
            'notification' => $notification,
        ]);
    }
}
