<?php

namespace App\Core\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Features\SystemSettings\Models\Setting;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get maintenance status from DB (cached)
        $isMaintenance = \App\Features\SystemSettings\Models\Setting::get('is_maintenance', '0') === '1';

        if ($isMaintenance) {
            // Bypass logic:
            // 1. Admin routes (/admin/*)
            // 2. Auth routes (/auth/*) for login/logout
            // 3. Health check (/up)
            // 4. Authenticated Admins (checked via role)
            $isAdminRoute = $request->is('admin*');
            $isAuthRoute  = $request->is('auth*');
            $isHealthCheck = $request->is('up');
            $isAdminUser  = Auth::check() && Auth::user()->role === 'admin';

            if ($isAdminRoute || $isAuthRoute || $isHealthCheck || $isAdminUser) {
                return $next($request);
            }

            // Handle Inertia requests differently to avoid SPA crashes
            if ($request->header('X-Inertia')) {
                return response('', 503, [
                    'X-Inertia-Location' => $request->url(),
                ]);
            }

            // Otherwise, show the maintenance page
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
