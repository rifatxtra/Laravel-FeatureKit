<?php

namespace App\Http\Middleware;

use App\Jobs\ProcessTrafficLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $responseTime = round((microtime(true) - $startTime) * 1000, 2); // ms
        $statusCode   = $response->getStatusCode();

        // Only track public routes — skip admin/api/ajax
        if (
            $request->is('admin/*', 'user/*', 'api/*', 'telescope*', '_debugbar*', 'sanctum/*') ||
            $request->ajax()
        ) {
            return $response;
        }

        // $request->ip() now correctly returns the real visitor IP because
        // TrustProxies is configured with '*' in bootstrap/app.php.
        // This handles nginx X-Forwarded-For, Cloudflare CF-Connecting-IP, etc.
        $ip = $request->ip() ?? '0.0.0.0';

        // Build session ID: hash of IP + UA + today's date (privacy-safe, no cookies)
        $sessionId = hash('sha256', $ip . '|' . $request->userAgent() . '|' . now()->format('Y-m-d'));

        ProcessTrafficLog::dispatch([
            'user_id'       => $request->user()?->id,
            'session_id'    => $sessionId,
            'ip_address'    => $ip,
            'uri'           => $request->getRequestUri(),
            'method'        => $request->method(),
            'status_code'   => $statusCode,
            'response_time' => $responseTime,
            'user_agent'    => $request->userAgent(),
            'referrer'      => $request->headers->get('referer'),
        ]);

        return $response;
    }
}
