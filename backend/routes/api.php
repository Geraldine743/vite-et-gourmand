<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlatController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ThemeController;
use App\Http\Controllers\Api\RegimeController;
use App\Http\Controllers\Api\AllergeneController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::apiResource('/allergenes',AllergeneController::class)->only(['index']);
Route::apiResource('/plats',PlatController::class)->only(['index']);
Route::apiResource('/regimes',RegimeController::class)->only(['index']);
Route::apiResource('/themes',ThemeController::class)->only(['index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    Route::middleware( 'is_staff')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::apiResource('/allergenes',AllergeneController::class)->except(['index','show']);
        Route::apiResource('/plats',PlatController::class)->except(['index','show']);
        Route::apiResource('/regimes',RegimeController::class)->except(['index','show']);
        Route::apiResource('/themes',ThemeController::class)->except(['index','show']);
    });

    Route::middleware('is_admin')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});