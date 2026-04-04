<?php

namespace App\Features\Auth\Controllers;

use App\Core\BaseController;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Services\LoginService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends BaseController
{
    public function __construct(
        protected LoginService $loginService
    ) {}

    public function index()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'admin') {
                return Inertia::location(route('admin.dashboard'));
            } else if ($role === 'user') {
                return Inertia::location(route('user.dashboard'));
            } else {
                abort(403);
            }
        }
        return view('pages.auth.login.page', [
            'app_name' => config('app.name'),
            'app_description' => config('app.description'),
            'title' => 'Login - Laravel Feature Kit',
            'keywords' => 'login, authentication, laravel feature kit',
            'description' => 'Login to your account on Laravel Feature Kit and access a world of powerful features and seamless user experience.',
            'og_image' => asset('assets/images/auth/login-og-image.png'),
            'og_title' => 'Login to Laravel Feature Kit',
            'og_description' => 'Login to your account on Laravel Feature Kit and access a world of powerful features and seamless user experience.',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $this->loginService->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        $user = Auth::user();

        if ($user->role === 'admin') {
            return Inertia::location(route('admin.dashboard'));
        } else if ($user->role === 'user') {
            return Inertia::location(route('user.dashboard'));
        } else {
            abort(403);
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return Inertia::location(route('login'));
    }
}
