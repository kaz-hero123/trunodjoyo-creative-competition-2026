<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AccountController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/check-in', [AssessmentController::class, 'create'])->name('check-in.create');
    Route::post('/check-in', [AssessmentController::class, 'store'])
        ->name('check-in.store')
        ->middleware('throttle:check-in');
    
    Route::get('/results/{assessment}', [AssessmentController::class, 'show'])->name('results.show');
    Route::post('/results/{assessment}/chat', [AssessmentController::class, 'chat'])->name('results.chat');
    
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/resource-matches/{match}/click', [ResourceController::class, 'recordClick'])->name('resources.click');
    
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
});
