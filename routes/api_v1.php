<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;
use App\Http\Middleware\RequireJson;

Route::post('register', RegistrationController::class)->name('auth.register');
Route::post('login', [LoginController::class, 'login'])->name('auth.login');

Route::post('forgot-password', [PasswordResetController::class, 'sendLink'])->name('auth.forgot-password')->middleware('throttle:1,60');
Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('auth.reset-password');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth:sanctum')->group(function() {

    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
});
