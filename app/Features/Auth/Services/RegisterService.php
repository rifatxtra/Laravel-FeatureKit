<?php

namespace App\Features\Auth\Services;

use App\Core\BaseService;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterService extends BaseService
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
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return $user;
    }
}
