<?php

use App\Features\Notification\Admin\Controllers\NotifciationController;
use Illuminate\Support\Facades\Route;

// Notification/Admin web routes
Route::middleware('auth', 'role:admin')->group(function () {
    Route::get('/notification', [NotifciationController::class, 'index'])->name('notifications.index');
    Route::get('/notification/{id}', [NotifciationController::class, 'read'])->name('notifications.read');
    Route::post('/notification/read-all', [NotifciationController::class, 'readAll'])->name('notifications.read-all');
});
