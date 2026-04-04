<?php

namespace App\Features\SystemSettings\Admin\Controllers;

use App\Core\BaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends BaseController
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        return Inertia::render('(portals)/admin/settings/page');
    }
}
