<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AssessmentController;
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
    Route::post('/results/{assessment}/chat', [AssessmentController::class, 'chat'])
        ->name('results.chat')
        ->middleware('throttle:chat-advisor');
    
    Route::prefix('workspace')->name('workspace.')->middleware('streak')->group(function () {
        Route::resource('notes', \App\Http\Controllers\Workspace\NoteController::class);
        
        Route::resource('flashcard-decks', \App\Http\Controllers\Workspace\FlashcardController::class)->except(['create', 'edit', 'update']);
        Route::post('flashcard-decks/{flashcard_deck}/generate', [\App\Http\Controllers\Workspace\FlashcardController::class, 'generate'])->name('flashcard-decks.generate');
        Route::post('flashcard-decks/{flashcard_deck}/cards', [\App\Http\Controllers\Workspace\FlashcardController::class, 'storeCard'])->name('flashcard-decks.cards.store');
        Route::patch('flashcards/{flashcard}/toggle-memorized', [\App\Http\Controllers\Workspace\FlashcardController::class, 'toggleMemorized'])->name('flashcards.toggle-memorized');

        Route::post('quizzes/generate', [\App\Http\Controllers\Workspace\QuizController::class, 'generate'])->name('quizzes.generate');
        Route::get('quizzes', [\App\Http\Controllers\Workspace\QuizController::class, 'history'])->name('quizzes.history');
        Route::get('quizzes/{quiz}', [\App\Http\Controllers\Workspace\QuizController::class, 'show'])->name('quizzes.show');
        Route::post('quizzes/{quiz}/submit', [\App\Http\Controllers\Workspace\QuizController::class, 'submit'])->name('quizzes.submit');
        Route::get('quizzes/{quiz}/review', [\App\Http\Controllers\Workspace\QuizController::class, 'review'])->name('quizzes.review');
    });

    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
});
