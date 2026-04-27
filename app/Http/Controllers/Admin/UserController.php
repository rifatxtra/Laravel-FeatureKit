<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\UserService;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $users = $this->userService->getPaginatedUsers($request->only(['search']));

        return Inertia::render('(portals)/admin/users/page', [
            'users' => $users,
            'filters' => $request->only(['search'])
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request)
    {
        $this->userService->createUser($request->validated());

        return redirect()->back()->with('success', 'User created successfully.');
    }

    /**
     * Update the specified user details.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot modify your own core privileges from this page.');
        }

        $this->userService->updateUser($user, $request->validated());

        return redirect()->back()->with('success', 'User updated successfully.');
    }
}
