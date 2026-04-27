<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Landing / Guest Routes ---
use App\Http\Controllers\Landing\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/documentation', [LandingController::class, 'docs'])->name('landing.docs');
Route::get('/features', [LandingController::class, 'features'])->name('landing.features');


// --- Auth Routes ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('auth.login.login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/register', [RegisterController::class, 'index'])->name('auth.register.index');
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register.register');

    Route::get('forgot-password', [ForgotPasswordController::class, 'index'])->name('auth.forgot-password.index');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('auth.forgot-password.send');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});


// --- Admin Routes ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivity;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\NotificationController as AdminNotification;
use App\Http\Controllers\Admin\ProfileController as AdminProfile;
use App\Http\Controllers\Admin\ProfileImageController as AdminProfileImage;
use App\Http\Controllers\Admin\HealthController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TrafficController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/activity-logs', [AdminActivity::class, 'index'])->name('activity-logs.index');

    Route::get('/cache', [CacheController::class, 'index'])->name('cache.index');
    Route::post('/cache/clear', [CacheController::class, 'clear'])->name('cache.clear');

    Route::get('/notification', [AdminNotification::class, 'index'])->name('notifications.index');
    Route::get('/notification/{id}', [AdminNotification::class, 'read'])->name('notifications.read');
    Route::post('/notification/read-all', [AdminNotification::class, 'readAll'])->name('notifications.read-all');

    Route::get('/profile', [AdminProfile::class, 'index'])->name('profile.index');
    Route::put('/profile', [AdminProfile::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [AdminProfile::class, 'passwordIndex'])->name('profile.password.index');
    Route::put('/profile/password', [AdminProfile::class, 'passwordUpdate'])->name('profile.password.update');
    Route::get('/profile-image/{filename}', [AdminProfileImage::class, 'serve'])->name('profile-image');

    Route::get('/system/health', [HealthController::class, 'index'])->name('system.health');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/logo', [SettingsController::class, 'updateLogo'])->name('settings.logo');
    Route::post('/settings/favicon', [SettingsController::class, 'updateFavicon'])->name('settings.favicon');

    Route::get('/traffic', [TrafficController::class, 'index'])->name('traffic.index');
    Route::get('/traffic/logs', [TrafficController::class, 'logs'])->name('traffic.logs');
    Route::get('/traffic/realtime', [TrafficController::class, 'realtime'])->name('traffic.realtime');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});


// --- User Routes ---
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\ActivityLogController as UserActivity;
use App\Http\Controllers\User\NotificationController as UserNotification;
use App\Http\Controllers\User\ProfileController as UserProfile;
use App\Http\Controllers\User\ProfileImageController as UserProfileImage;

Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    Route::get('/activity-logs', [UserActivity::class, 'index'])->name('activity-logs.index');

    Route::get('/notification', [UserNotification::class, 'index'])->name('notification.index');
    Route::post('/notification/read-all', [UserNotification::class, 'readAll'])->name('notification.read-all');
    Route::get('/notification/{id}', [UserNotification::class, 'read'])->name('notification.read');

    Route::get('/profile', [UserProfile::class, 'index'])->name('profile.index');
    Route::put('/profile', [UserProfile::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [UserProfile::class, 'passwordIndex'])->name('profile.password.index');
    Route::put('/profile/password', [UserProfile::class, 'passwordUpdate'])->name('profile.password.update');
    Route::get('/profile-image/{filename}', [UserProfileImage::class, 'serve'])->name('profile-image');
});
