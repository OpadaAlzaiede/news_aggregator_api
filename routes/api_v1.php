<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\PasswordResetController;

Route::post('register', RegistrationController::class)->name('users.register');
Route::post('login', [LoginController::class, 'login'])->name('auth.login');

Route::post('forgot-password', [PasswordResetController::class, 'sendLink'])->name('auth.forgot-password')->middleware('throttle:1,60');
Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('auth.reset-password');

Route::middleware('auth:sanctum')->group(function() {

    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
});
