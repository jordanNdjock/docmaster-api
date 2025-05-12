<?php

use App\Http\Controllers\Api\V1\AbonnementController;
use App\Http\Controllers\Api\V1\AdminController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DocmasterController;
use App\Http\Controllers\Api\V1\DocumentController;
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

    /**
     * Gestion des documents
     */
    Route::post('document/{id}/restore', [DocumentController::class, 'restore']);
    Route::delete('document/{id}/force-delete', [DocumentController::class, 'forceDelete']);
    Route::get('document/archived', [DocumentController::class, 'archived']);
    Route::apiresource('document', DocumentController::class);

    /**
     * Souscrire à un abonnement
     */
    Route::post('abonnement/{id}/subscribe', [AbonnementController::class, 'subscribe']);

    /**
     * Gestion des docmasters
     */
    Route::post('declaration/{id}/restore', [DocmasterController::class, 'restore']);
    Route::delete('declaration/{id}/force-delete', [DocmasterController::class, 'forceDelete']);
    Route::get('declaration/archived', [DocmasterController::class, 'archived']);
    Route::get('declaration/search', [DocmasterController::class, 'search']);
    Route::apiResource('declaration', DocmasterController::class)
        ->only(['index', 'show', 'update', 'destroy', 'store']);
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
    Route::post('type-document/{id}/restore', [TypeDocumentController::class, 'restore']);
    Route::delete('type-document/{id}/force-delete', [TypeDocumentController::class, 'forceDelete']);
    Route::get('type-document/archived', [TypeDocumentController::class, 'archived']);
    Route::apiResource('type-document', TypeDocumentController::class);

    /**
     * Gestion des documents
     */
    Route::get('documents', [DocumentController::class, 'indexAdmin']);

    /** 
     * Gestion des abonnements
     */
    Route::post('abonnements/{id}/restore', [AbonnementController::class, 'restore']);
    Route::delete('abonnements/{id}/force-delete', [AbonnementController::class, 'forceDelete']);
    Route::get('abonnements/archived', [AbonnementController::class, 'archived']);
    Route::apiResource('abonnements', AbonnementController::class);

    /** 
     * Gestion des docmasters
    */
    Route::delete('declaration/{id}/force-delete', [DocmasterController::class, 'forceDelete']);
    Route::apiResource('declaration', DocmasterController::class)
        ->only(['index', 'show']);
});

