<?php

namespace App\Features\SystemSettings\Admin\Controllers;

use App\Core\BaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Features\SystemSettings\Models\Setting;
use App\Features\SystemSettings\Admin\Services\SettingsService;

class SettingsController extends BaseController
{
    public function __construct(
        protected SettingsService $settingsService
    ) {}

    /**
     * Display the settings page with current values.
     */
    public function index()
    {
        return Inertia::render('(portals)/admin/settings/page', [
            'settings' => Setting::getAll()
        ]);
    }

    /**
     * Update general settings.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'app_name'       => 'nullable|string|max:255',
            'is_maintenance'       => 'nullable|boolean',
            'maintenance_duration' => 'nullable|string|max:100',
            'email_support'        => 'nullable|email|max:255',
        ]);

        $this->settingsService->updateGeneralSettings($data);

        return back()->with('success', 'General settings updated successfully.');
    }

    /**
     * Update application logo.
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $this->settingsService->updateLogo($request->file('logo'));

        return back()->with('success', 'Application logo updated successfully.');
    }

    /**
     * Update application favicon.
     */
    public function updateFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:png,jpg,jpeg,webp|max:1024',
        ]);

        $this->settingsService->updateFavicon($request->file('favicon'));

        return back()->with('success', 'Application favicon updated successfully.');
    }
}
