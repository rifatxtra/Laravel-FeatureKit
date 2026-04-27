<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location('/');
        }
        return view('pages.home.page');
    }

    public function docs(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(route('landing.docs'));
        }
        return view('pages.documentation.page');
    }

    public function features(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(route('landing.features'));
        }
        return view('pages.features.page');
    }
}
