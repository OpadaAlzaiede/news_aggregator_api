<?php

use App\Http\Controllers\Api\V1\User\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegistrationController::class)->name('users.register');
