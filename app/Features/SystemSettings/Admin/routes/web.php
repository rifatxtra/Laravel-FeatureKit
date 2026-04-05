<?php

use Illuminate\Support\Facades\Route;
use App\Features\SystemSettings\Admin\Controllers\SettingsController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/logo', [SettingsController::class, 'updateLogo'])->name('settings.logo');
    Route::post('/settings/favicon', [SettingsController::class, 'updateFavicon'])->name('settings.favicon');
});
