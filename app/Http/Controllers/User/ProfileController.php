<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Services\User\ProfileService;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function index()
    {
        return Inertia::render('(portals)/user/profile/page');
    }

    public function update(UpdateProfileRequest $request)
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return back()->with('success', 'Profile updated successfully.');
    }

    public function passwordIndex()
    {
        return Inertia::render('(portals)/user/profile/password/page');
    }

    public function passwordUpdate(UpdatePasswordRequest $request)
    {
        $this->profileService->updatePassword($request->user(), $request->password);

        return back()->with('success', 'Password changed successfully.');
    }
}
