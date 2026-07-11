<?php

use App\Http\Controllers\Api\UnityBridgeController;
use App\Http\Controllers\Api\VRSessionsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NeuroController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\VRAssetController;
use App\Http\Controllers\PatientAssignmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\OutcomesController;
use App\Http\Controllers\CrisisAlertController;
use App\Http\Controllers\TreatmentPlanController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/impact', function () {
    return view('impact');
})->name('impact');

Route::get('/research', function () {
    return view('research');
})->name('research');

Route::get('/library', function () {
    return view('library');
})->name('library');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
Route::get('/register-choice', [AuthController::class, 'showRegisterChoice'])->name('register.choice');
Route::get('/register/{type?}', [AuthController::class, 'showRegister'])->name('register')->where('type', 'patient|therapist');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [NeuroController::class, 'dashboard'])->name('dashboard');
    Route::get('/session', [NeuroController::class, 'session'])->name('session');
    Route::get('/review', [NeuroController::class, 'review'])->name('review');
    Route::get('/settings', [NeuroController::class, 'settings'])->name('settings');
    Route::post('/settings/password', [NeuroController::class, 'updatePassword'])->name('settings.password');
    Route::get('/onboarding', [NeuroController::class, 'onboarding'])->name('onboarding');
    Route::post('/onboarding/complete', [NeuroController::class, 'completeOnboarding'])->name('onboarding.complete');
    Route::get('/mood-tracker', [NeuroController::class, 'moodTracker'])->name('mood.tracker');
    Route::post('/mood', [MoodController::class, 'store'])->name('mood.store');
    Route::get('/therapy-sessions', [NeuroController::class, 'therapySessions'])->name('therapy.sessions');
    Route::get('/progress-tracking', [NeuroController::class, 'progressTracking'])->name('progress.tracking');
    Route::get('/vr-assets', [VRAssetController::class, 'index'])->name('vr.assets');
    Route::get('/vr-assets/{vrAsset}', [VRAssetController::class, 'show'])->name('vr.assets.show');
    Route::get('/vr-analytics', [NeuroController::class, 'vrAnalytics'])->name('vr.analytics');

    // Assessments
    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/{type}/take', [AssessmentController::class, 'show'])->name('assessments.take');
    Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/assessments/{type}/results', [AssessmentController::class, 'results'])->name('assessments.results');
    Route::get('/assessments/report/{assessmentResult}', [AssessmentController::class, 'report'])->name('assessments.report');
    Route::get('/assessments/history/all', [AssessmentController::class, 'history'])->name('assessments.history');

    // Outcomes
    Route::get('/outcomes', [OutcomesController::class, 'index'])->name('outcomes.index');
    Route::get('/outcomes/patient/{patient}', [OutcomesController::class, 'patientReport'])->name('outcomes.patient');

    // Patient therapist selection
    Route::get('/my-therapist', [NeuroController::class, 'therapistSelection'])->name('patient.therapist');
    Route::post('/my-therapist/select', [NeuroController::class, 'selectTherapist'])->name('patient.therapist.select');

    // Weekly mood data API
    Route::get('/api/moods/weekly', [MoodController::class, 'getWeeklyData'])->name('moods.weekly');

    // Export report
    Route::get('/export/mood-report', [NeuroController::class, 'exportMoodReport'])->name('export.mood-report');

    // Crisis Alerts
    Route::get('/crisis-alerts', [CrisisAlertController::class, 'index'])->name('crisis-alerts.index');
    Route::post('/crisis-alerts/{crisisAlert}/resolve', [CrisisAlertController::class, 'resolve'])->name('crisis-alerts.resolve');

    // Patient-facing treatment plans
    Route::get('/my-treatment-plans', [TreatmentPlanController::class, 'index'])->name('patient.treatment-plans');
    Route::get('/my-treatment-plans/{treatmentPlan}', [TreatmentPlanController::class, 'show'])->name('patient.treatment-plans.show');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/api/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('/api/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/api/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/api/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/api/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.delete-all-read');

    // VR Asset API routes
    Route::post('/api/vr-assets/{vrAsset}/usage', [VRAssetController::class, 'incrementUsage']);
    Route::get('/api/vr-assets/category/{category}', [VRAssetController::class, 'byCategory']);
    Route::get('/api/vr-assets/popular', [VRAssetController::class, 'popular']);
    Route::get('/api/vr-assets/top-rated', [VRAssetController::class, 'topRated']);

    // VR Session API routes
    Route::post('/api/vr-sessions/start', [VRSessionsController::class, 'start']);
    Route::post('/api/vr-sessions/end', [VRSessionsController::class, 'end']);
    Route::get('/api/user/current-mood', [VRSessionsController::class, 'getCurrentMood']);

    // Unity Bridge API routes
    Route::post('/api/vr/launch-unity', [UnityBridgeController::class, 'launch']);
    Route::post('/api/vr/check-status', [UnityBridgeController::class, 'checkStatus']);
    Route::get('/api/vr/scenes', [UnityBridgeController::class, 'scenes']);
    Route::post('/api/vr/end-session', [UnityBridgeController::class, 'endSession']);
    Route::get('/api/vr/config', [UnityBridgeController::class, 'config']);
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'userDetails'])->name('user.details');
    Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('user.update-role');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateUserRole']);
    Route::post('/users', [AdminController::class, 'createUser'])->name('user.create');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('user.delete');
    
    // VR Asset Management
    Route::get('/vr-assets', [VRAssetController::class, 'adminList'])->name('vr-assets.list');
    Route::get('/vr-assets/create', [VRAssetController::class, 'create'])->name('vr-assets.create');
    Route::post('/vr-assets', [VRAssetController::class, 'store'])->name('vr-assets.store');
    Route::get('/vr-assets/{vrAsset}/edit', [VRAssetController::class, 'edit'])->name('vr-assets.edit');
    Route::put('/vr-assets/{vrAsset}', [VRAssetController::class, 'update'])->name('vr-assets.update');
    Route::delete('/vr-assets/{vrAsset}', [VRAssetController::class, 'destroy'])->name('vr-assets.destroy');
});

// Therapist routes
Route::middleware(['auth', 'therapist'])->prefix('therapist')->name('therapist.')->group(function () {
    Route::get('/dashboard', [TherapistController::class, 'dashboard'])->name('dashboard');
    Route::get('/reports', [TherapistController::class, 'reports'])->name('reports');
    Route::get('/patients', [TherapistController::class, 'patients'])->name('patients');
    Route::get('/patients/{patient}', [TherapistController::class, 'patientDetails'])->name('patient.details');
    
    // Patient assignment routes
    Route::get('/assignments', [PatientAssignmentController::class, 'index'])->name('assignments.index');
    Route::post('/assignments/assign', [PatientAssignmentController::class, 'assign'])->name('assignments.assign');
    Route::post('/assignments/{patient}/remove', [PatientAssignmentController::class, 'remove'])->name('assignments.remove');
    Route::post('/assignments/{patient}/notes', [PatientAssignmentController::class, 'updateNotes'])->name('assignments.update-notes');

    // VR Session Report
    Route::get('/vr-sessions/{vrSession}', [TherapistController::class, 'vrSessionReport'])->name('vr-session.report');

    // Treatment Plans
    Route::get('/treatment-plans', [TreatmentPlanController::class, 'index'])->name('treatment-plans.index');
    Route::get('/treatment-plans/create', [TreatmentPlanController::class, 'create'])->name('treatment-plans.create');
    Route::post('/treatment-plans', [TreatmentPlanController::class, 'store'])->name('treatment-plans.store');
    Route::get('/treatment-plans/{treatmentPlan}', [TreatmentPlanController::class, 'show'])->name('treatment-plans.show');
    Route::post('/treatment-plans/{treatmentPlan}/milestones', [TreatmentPlanController::class, 'addMilestone'])->name('treatment-plans.milestones.store');
    Route::post('/treatment-plans/milestones/{planMilestone}/complete', [TreatmentPlanController::class, 'completeMilestone'])->name('treatment-plans.milestones.complete');
    Route::post('/treatment-plans/{treatmentPlan}/status', [TreatmentPlanController::class, 'updateStatus'])->name('treatment-plans.status');
});
