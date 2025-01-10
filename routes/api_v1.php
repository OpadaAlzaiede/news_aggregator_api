<?php

use App\Http\Controllers\Api\V1\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Controllers\Api\V1\UserPreferenceController;
use App\Http\Middleware\RequireJson;

Route::middleware(RequireJson::class)->group(function() {

    /* Auth Routes */
    Route::post('register', RegistrationController::class)->name('auth.register');
    Route::post('login', [LoginController::class, 'login'])->name('auth.login');

    Route::post('forgot-password', [PasswordResetController::class, 'sendLink'])->name('auth.forgot-password')->middleware('throttle:1,60');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('auth.reset-password');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->withoutMiddleware(RequireJson::class)
        ->name('verification.verify');


    Route::middleware('auth:sanctum')->group(function() {

        /* Article Routes */
        Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('articles/by-keyword', [ArticleController::class, 'indexByKeyword'])->name('articles.indexByKeyword');
        Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

        /* Preferences Routes */
        Route::get('preferences', [UserPreferenceController::class, 'index'])->name('preferences.index');
        Route::post('preferences', [UserPreferenceController::class, 'store'])->name('preferences.store')->middleware('throttle:10,1');
        Route::delete('preferences', [UserPreferenceController::class, 'destroy'])->name('preferences.destroy');


        Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
    });

});
