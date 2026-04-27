<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Services\Admin\HealthStatusService;

class HealthController extends Controller
{
    public function __construct(
        protected HealthStatusService $healthService
    ) {}

    /**
     * Display the system health page.
     */
    public function index()
    {
        return Inertia::render('(portals)/admin/system/health/page', [
            'metrics' => $this->healthService->getSystemMetrics()
        ]);
    }
}
