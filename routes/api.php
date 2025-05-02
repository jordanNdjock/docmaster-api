<?php

use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TypeDocumentController;
use Illuminate\Support\Facades\Route;

// Authentification des utilisateurs
Route::group(['prefix' => 'auth', 'middleware' => 'throttle:10,1'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    /**
     * Gestion du profil et deconnexion
     */
    Route::post('auth/logout', [AuthController::class, 'logout']);


    /**
     * Gestion des types de documents
     */
    Route::apiResource('type-document', TypeDocumentController::class)
        ->only(['index', 'show']);
});



//Côté admin

//Authentification des admins
Route::group(['prefix' => 'admin/auth', 'middleware' => 'throttle:10,1'], function () {
    Route::post('login', [AdminController::class, 'login']);
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    /**
     * Gestion du profil admin et deconnexion
     */
    Route::post('auth/logout', [AdminController::class, 'logout']);

    /**
     * Gestion des types de documents
     */
    Route::apiResource('type-document', TypeDocumentController::class);   
});

