<?php

namespace App\Services\Auth;

use App\Services\Service;
use App\Events\ActivityLogged;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterService extends Service
{
    /**
     * Handle the registration of a new user.
     *
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'role'              => 'user',
            'is_active'         => true,
            'email_verified_at' => now(),
            'last_login_at'     => now(),
            'last_login_ip'     => $data['last_login_ip'],
        ]);

        Auth::login($user);

        event(new ActivityLogged($user, 'register', 'New user account created.', 'security'));

        return $user;
    }
}
