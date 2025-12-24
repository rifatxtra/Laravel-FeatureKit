<?php

namespace App\Features\Home\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the welcome page
     */
    public function index(): Response
    {
        return Inertia::render('Welcome');
    }
}
