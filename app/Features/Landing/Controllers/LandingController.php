<?php

namespace App\Features\Landing\Controllers;

use App\Core\BaseController;

class LandingController extends BaseController
{
    public function index()
    {
        return view('pages.home.page');
    }

    public function docs()
    {
        return view('pages.home.docs');
    }

    public function features()
    {
        return view('pages.home.features');
    }
}
