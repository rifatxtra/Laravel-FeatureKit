<?php

use App\Features\Notification\User\Controllers\NotifciationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Notification/User web routes
Route::middleware('auth', 'role:user')->group(function () {
    Route::get('/notification', [NotifciationController::class, 'index'])->name('notification.index');
    Route::post('/notification/read-all', [NotifciationController::class, 'readAll'])->name('notification.read-all');
    Route::get('/notification/{id}', [NotifciationController::class, 'read'])->name('notification.read');
});
