<?php

namespace App\Features\Auth\Controllers;

use App\Core\BaseController;
use App\Features\Auth\Requests\RegisterRequest;
use App\Features\Auth\Services\RegisterService;

class RegisterController extends BaseController
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
