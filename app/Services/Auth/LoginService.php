<?php

namespace App\Services\Auth;

use App\Services\Service;
use App\Events\ActivityLogged;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginService extends Service
{
    /**
     * Handle the login attempt.
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     * @throws ValidationException
     */
    public function attempt(array $credentials, bool $remember = false): bool
    {
        if (Auth::attempt($credentials, $remember)) {
            request()->session()->regenerate();
            User::where('id', Auth::id())->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);

            event(new ActivityLogged(Auth::user(), 'login', 'User logged in successfully.', 'security'));

            return true;
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
