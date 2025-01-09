<?php

use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegistrationController::class)->name('users.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function() {



    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});
