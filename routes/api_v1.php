<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\FeedController;
use App\Http\Controllers\Api\V1\UserPreferenceController;
use App\Http\Middleware\RequireJsonMiddleware;
use App\Http\Middleware\UserHasPreferencesMiddleware;
use App\Http\Middleware\VerifiedEmailMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(RequireJsonMiddleware::class)->group(function () {

    /* Auth Routes */
    Route::post('register', RegistrationController::class)->name('auth.register');
    Route::post('login', LoginController::class)->name('auth.login')->middleware(VerifiedEmailMiddleware::class);

    Route::post('forgot-password', [PasswordResetController::class, 'sendLink'])->name('auth.forgot-password')->middleware('throttle:1,60');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('auth.reset-password');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->withoutMiddleware(RequireJsonMiddleware::class)
        ->name('verification.verify');

    Route::middleware('auth:sanctum')->group(function () {

        /* Article Routes */
        Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

        /* Preferences Routes */
        Route::get('preferences', [UserPreferenceController::class, 'index'])->name('preferences.index');
        Route::post('preferences', [UserPreferenceController::class, 'store'])->name('preferences.store')->middleware('throttle:10,1');
        Route::delete('preferences', [UserPreferenceController::class, 'destroy'])->name('preferences.destroy');

        Route::get('feed', [FeedController::class, 'index'])->name('feed.index')->middleware(UserHasPreferencesMiddleware::class);

        Route::post('logout', LogoutController::class)->name('auth.logout');
    });

});
