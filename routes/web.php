<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorReviewController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AiProviderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\EmergencyAlertController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('front-page');
})->name('front-page');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            default => redirect()->route('chat.index'),
        };
    })->name('dashboard');

    // ==========================================================
    // ===== DIARY - Full CRUD for All Authenticated Users =====
    // ==========================================================
    Route::prefix('diary')->name('diary.')->group(function () {
        Route::get('/', [DiaryController::class, 'index'])->name('index');
        Route::post('/', [DiaryController::class, 'store'])->name('store');
        Route::get('/{diary}/edit', [DiaryController::class, 'edit'])->name('edit');
        Route::put('/{diary}', [DiaryController::class, 'update'])->name('update');
        Route::delete('/{diary}', [DiaryController::class, 'destroy'])->name('destroy');
        Route::patch('/{diary}/toggle-share', [DiaryController::class, 'toggleShare'])->name('toggle-share');
        Route::get('/{diary}', [DiaryController::class, 'show'])->name('show');
    });

    // ==========================================================
    // ===== USER ROUTES =====
    // ==========================================================
    Route::middleware('role:user')->group(function () {
        // Chat
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::post('/start', [ChatController::class, 'start'])->name('start');
            Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
            Route::post('/{conversation}/send', [ChatController::class, 'send'])->name('send');
            Route::delete('/{conversation}', [ChatController::class, 'destroy'])->name('destroy');
        });

        // ===== SOCIAL POSTS (Full CRUD) =====
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::get('/create', [PostController::class, 'create'])->name('create');
            Route::post('/', [PostController::class, 'store'])->name('store');
            Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
            Route::put('/{post}', [PostController::class, 'update'])->name('update');
            Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
            Route::post('/{post}/like', [PostController::class, 'like'])->name('like');
            Route::post('/{post}/comment', [PostController::class, 'comment'])->name('comment');
        });

        // Social alias for posts index
        Route::get('/social', [PostController::class, 'index'])->name('social');
    });

    // ==========================================================
    // ===== DOCTOR ROUTES =====
    // ==========================================================
    Route::middleware('role:doctor')
        ->prefix('doctor')
        ->name('doctor.')
        ->group(function () {
            Route::get('/', [DoctorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/conversations/{conversation}', [DoctorDashboardController::class, 'show'])->name('conversations.show');
            Route::post('/reviews', [DoctorReviewController::class, 'store'])->name('reviews.store');

            // 🚨 EMERGENCY ALERTS - DOCTOR
            Route::prefix('emergency-alerts')->name('emergency-alerts.')->group(function () {
                Route::get('/', [EmergencyAlertController::class, 'index'])->name('index');
                Route::get('/{alert}', [EmergencyAlertController::class, 'show'])->name('show');
                Route::post('/{alert}/resolve', [EmergencyAlertController::class, 'resolve'])->name('resolve');
                Route::post('/resolve-all', [EmergencyAlertController::class, 'resolveAll'])->name('resolve-all');
            });
        });

    // ==========================================================
    // ===== ADMIN ROUTES =====
    // ==========================================================
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

            // AI Providers
            Route::prefix('ai-providers')->name('ai-providers.')->group(function () {
                Route::get('/', [AiProviderController::class, 'index'])->name('index');
                Route::get('/create', [AiProviderController::class, 'create'])->name('create');
                Route::post('/', [AiProviderController::class, 'store'])->name('store');
                Route::patch('/{aiProvider}', [AiProviderController::class, 'update'])->name('update');
                Route::delete('/{aiProvider}', [AiProviderController::class, 'destroy'])->name('destroy');
                Route::patch('/{aiProvider}/activate', [AiProviderController::class, 'activate'])->name('activate');
                Route::patch('/{aiProvider}/deactivate', [AiProviderController::class, 'deactivate'])->name('deactivate');
            });

            // Users
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');
                Route::post('/', [AdminController::class, 'store'])->name('store');
                Route::patch('/{user}', [AdminController::class, 'update'])->name('update');
                Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy');
            });

            // 🚨 EMERGENCY ALERTS - ADMIN
            Route::prefix('emergency-alerts')->name('emergency-alerts.')->group(function () {
                Route::get('/', [EmergencyAlertController::class, 'index'])->name('index');
                Route::get('/{alert}', [EmergencyAlertController::class, 'show'])->name('show');
                Route::post('/{alert}/resolve', [EmergencyAlertController::class, 'resolve'])->name('resolve');
                Route::post('/resolve-all', [EmergencyAlertController::class, 'resolveAll'])->name('resolve-all');
                Route::get('/stats', [EmergencyAlertController::class, 'stats'])->name('stats');
            });
        });

    // ==========================================================
    // ===== PROFILE =====
    // ==========================================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Conversations Resource (fallback)
    Route::resource('conversations', ConversationController::class);
});

require __DIR__.'/auth.php';