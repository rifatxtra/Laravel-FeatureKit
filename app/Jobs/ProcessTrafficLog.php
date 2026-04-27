<?php

namespace App\Jobs;

use App\Models\TrafficLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessTrafficLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $parsed = $this->parseUserAgent($this->data['user_agent']);

        // Determine if new visitor: first visit from this IP today
        $isNewVisitor = ! TrafficLog::where('ip_address', $this->data['ip_address'])
            ->where('created_at', '<', Carbon::today())
            ->exists();

        // Geo lookup (lightweight, no extra package)
        $geo = $this->resolveGeo($this->data['ip_address']);

        TrafficLog::create([
            'user_id'       => $this->data['user_id'],
            'session_id'    => $this->data['session_id'],
            'ip_address'    => $this->data['ip_address'],
            'uri'           => $this->data['uri'],
            'method'        => $this->data['method'],
            'status_code'   => $this->data['status_code'],
            'response_time' => $this->data['response_time'],
            'user_agent'    => $this->data['user_agent'],
            'browser'       => $parsed['browser'],
            'os'            => $parsed['os'],
            'device_type'   => $parsed['device_type'],
            'referrer'      => $this->data['referrer'],
            'country_code'  => $geo['country_code'],
            'country_name'  => $geo['country_name'],
            'is_bot'        => $parsed['is_bot'],
            'is_new_visitor' => $isNewVisitor,
        ]);
    }

    /**
     * Resolve country information from IP address.
     * Uses ip-api.com (free, no auth, 45 req/min limit — ideal for most apps).
     * Returns empty data gracefully if the request fails or IP is private.
     */
    private function resolveGeo(string $ip): array
    {
        $default = ['country_code' => null, 'country_name' => null];

        // Skip geo for private/loopback IPs (same logic as middleware guard)
        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $default;
        }

        try {
            $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode");
            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success') {
                    return [
                        'country_code' => $data['countryCode'] ?? null,
                        'country_name' => $data['country'] ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Silently swallow — geo is non-critical
        }

        return $default;
    }

    /**
     * Enhanced User-Agent Parser Logic.
     */
    private function parseUserAgent(?string $ua): array
    {
        $os          = 'Unknown';
        $browser     = 'Unknown';
        $device_type = 'Desktop';
        $is_bot      = false;

        if (empty($ua)) {
            return compact('os', 'browser', 'device_type', 'is_bot');
        }

        // Comprehensive Bot Detection
        $bots = [
            'bot',
            'crawler',
            'spider',
            'slurp',
            'google',
            'bing',
            'yandex',
            'duckduckgo',
            'baiduspider',
            'facebookexternalhit',
            'twitterbot',
            'rogerbot',
            'linkedinbot',
            'embedly',
            'quora',
            'showyoubot',
            'outbrain',
            'pinterest',
            'developers.google.com',
            'Lighthouse',
            'HeadlessChrome',
            'PhantomJS',
        ];
        foreach ($bots as $bot) {
            if (stripos($ua, $bot) !== false) {
                $is_bot = true;
                break;
            }
        }

        // Device Type
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $ua)) {
            $device_type = 'Tablet';
        } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile|mobile)/i', $ua)) {
            $device_type = 'Mobile';
        }

        // OS Detection
        if (preg_match('/windows nt 10/i', $ua)) {
            $os = 'Windows 10/11';
        } elseif (preg_match('/windows nt 6\.3/i', $ua)) {
            $os = 'Windows 8.1';
        } elseif (preg_match('/windows/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'Mac OS';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        } elseif (preg_match('/android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/ubuntu/i', $ua)) {
            $os = 'Ubuntu';
        } elseif (preg_match('/chromeos/i', $ua)) {
            $os = 'Chrome OS';
        }

        // Browser Detection (order matters — check specific before general)
        if (preg_match('/opera|opr/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/edg\//i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/brave/i', $ua)) {
            $browser = 'Brave';
        } elseif (preg_match('/chrome|crios/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox|fxios/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/msie|trident/i', $ua)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/samsung/i', $ua)) {
            $browser = 'Samsung Browser';
        } elseif (preg_match('/UCBrowser/i', $ua)) {
            $browser = 'UC Browser';
        }

        return compact('os', 'browser', 'device_type', 'is_bot');
    }
}
