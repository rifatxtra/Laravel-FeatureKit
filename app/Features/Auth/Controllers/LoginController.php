<?php

namespace App\Features\Auth\Controllers;

use App\Core\BaseController;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Services\LoginService;

class LoginController extends BaseController
{
    public function __construct(
        protected LoginService $loginService
    ) {}

    public function index()
    {
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

        return redirect()->intended(route('home.index'));
    }
}
