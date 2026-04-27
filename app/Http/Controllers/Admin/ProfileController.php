<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Services\Admin\ProfileService;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Models\User;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        return Inertia::render('(portals)/admin/profile/page');
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return back()->with('success', 'Profile updated successfully.');
    }

    public function passwordIndex()
    {
        return Inertia::render('(portals)/admin/profile/password/page');
    }

    public function passwordUpdate(UpdatePasswordRequest $request)
    {
        $this->profileService->updatePassword($request->user(), $request->password);

        return back()->with('success', 'Password changed successfully.');
    }
}
