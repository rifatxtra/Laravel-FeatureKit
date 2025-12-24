<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset form.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $request->route('token'),
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Handle password reset request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/login')->with('success', 'Password reset successfully! You can now login.');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
