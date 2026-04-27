<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;

class RegisterController extends Controller
{
    public function __construct(
        protected RegisterService $registerService
    ) {}

    public function index()
    {
        return view('pages.auth.register.page', [
            'title'       => 'Create Account - Laravel Feature Kit',
            'description' => 'Join Laravel Feature Kit today and start building amazing applications.',
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $this->registerService->register($request->validated());

        return inertia('(portals)/user/dashboard/page', [
            'title'       => 'User Dashboard',
        ]);
    }
}
