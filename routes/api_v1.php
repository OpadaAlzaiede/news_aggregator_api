<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;

Route::post('register', RegistrationController::class)->name('users.register');
Route::post('login', [LoginController::class, 'login'])->name('auth.login');

Route::middleware('auth:sanctum')->group(function() {



    Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');
});
