<?php

namespace App\Features\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ForgotPasswordController extends Controller
{
    /**
     * Display the password reset request form.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Handle password reset link request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent to your email!');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
