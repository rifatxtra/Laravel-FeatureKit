<?php

use Illuminate\Support\Facades\Route;
use App\Features\Auth\Controllers\LoginController;
use App\Features\Auth\Controllers\RegisterController;
use App\Features\Auth\Controllers\LogoutController;
use App\Features\Auth\Controllers\ForgotPasswordController;
use App\Features\Auth\Controllers\ResetPasswordController;

// Guest routes (only accessible when not authenticated)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    
    // Register
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store']);
    
    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
});
