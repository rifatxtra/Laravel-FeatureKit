<?php

namespace App\Features\Auth\Models;

use App\Features\Notification\Admin\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
        'profile_image',
        'last_login_at',
        'last_login_ip',

    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'last_login_at',
        'last_login_ip',
    ];

    public $timestamps = true;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
