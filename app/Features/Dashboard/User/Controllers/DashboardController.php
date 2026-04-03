<?php

namespace App\Features\Dashboard\User\Controllers;

use App\Core\BaseController;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\title;

class DashboardController extends BaseController
{
    public function index()
    {
        return inertia('(portals)/user/dashboard/page', [
            'user' => Auth::user()
        ])->withViewData([
            'title' => 'User Dashboard'
        ]);
    }
}
