<?php

use App\Features\Dashboard\Admin\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Dashboard/Admin web routes
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
