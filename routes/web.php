<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VRSessionsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NeuroController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\TherapistController;

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public fallback redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [NeuroController::class, 'dashboard'])->name('dashboard');
    Route::get('/mood-tracker', [NeuroController::class, 'moodTracker'])->name('mood.tracker');
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');
    Route::get('/therapy-sessions', [NeuroController::class, 'therapySessions'])->name('therapy.sessions');
    Route::get('/progress-tracking', [NeuroController::class, 'progressTracking'])->name('progress.tracking');
    Route::get('/vr-assets', [NeuroController::class, 'vrAssets'])->name('vr.assets');
    Route::get('/vr-analytics', [NeuroController::class, 'vrAnalytics'])->name('vr.analytics');

    // VR Session API routes
    Route::post('/api/vr-sessions/start', [VRSessionsController::class, 'start']);
    Route::post('/api/vr-sessions/end', [VRSessionsController::class, 'end']);
    Route::get('/api/user/current-mood', [VRSessionsController::class, 'getCurrentMood']);
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'userDetails'])->name('user.details');
    Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('user.update-role');
    Route::post('/users', [AdminController::class, 'createUser'])->name('user.create');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('user.delete');
});

// Therapist routes
Route::middleware(['auth', 'therapist'])->prefix('therapist')->name('therapist.')->group(function () {
    Route::get('/dashboard', [TherapistController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [TherapistController::class, 'patients'])->name('patients');
    Route::get('/patients/{patient}', [TherapistController::class, 'patientDetails'])->name('patient.details');
});
