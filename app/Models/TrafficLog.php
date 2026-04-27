<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'uri',
        'method',
        'status_code',
        'response_time',
        'user_agent',
        'browser',
        'os',
        'device_type',
        'referrer',
        'country_code',
        'country_name',
        'is_bot',
        'is_new_visitor',
    ];

    protected $casts = [
        'is_bot'         => 'boolean',
        'is_new_visitor' => 'boolean',
        'response_time'  => 'float',
        'status_code'    => 'integer',
    ];

    public $timestamps = true;

    /**
     * Relationship to User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for real users only (excluding bots).
     */
    public function scopeRealUsers($query)
    {
        return $query->where('is_bot', false);
    }

    /**
     * Scope for unique visitors based on IP and URI within a timeframe.
     */
    public function scopeUnique($query)
    {
        return $query->groupBy('ip_address', 'uri');
    }

    /**
     * Scope for new visitors.
     */
    public function scopeNewVisitors($query)
    {
        return $query->where('is_new_visitor', true);
    }

    /**
     * Scope for returning visitors.
     */
    public function scopeReturningVisitors($query)
    {
        return $query->where('is_new_visitor', false);
    }

    /**
     * Scope for successful requests only (2xx).
     */
    public function scopeSuccessful($query)
    {
        return $query->whereBetween('status_code', [200, 299]);
    }

    /**
     * Scope for error requests (4xx, 5xx).
     */
    public function scopeErrors($query)
    {
        return $query->where('status_code', '>=', 400);
    }
}
