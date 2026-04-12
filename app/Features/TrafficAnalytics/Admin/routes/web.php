<?php

use App\Features\TrafficAnalytics\Admin\Controllers\TrafficController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/traffic',         [TrafficController::class, 'index'])->name('traffic.index');
    Route::get('/traffic/logs',    [TrafficController::class, 'logs'])->name('traffic.logs');
    Route::get('/traffic/realtime',[TrafficController::class, 'realtime'])->name('traffic.realtime');
});
