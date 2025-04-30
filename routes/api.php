<?php

use App\Http\Api\V1\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth', 'middleware' => 'throttle:10,1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('auth/logout', [AuthController::class, 'logout']);
});

