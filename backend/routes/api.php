<?php
use App\Http\Controllers\Api\AllergeneController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AvisController;
use App\Http\Controllers\Admin\ChiffreAffaireController;
use App\Http\Controllers\Api\CommandeController;
use App\Http\Controllers\Api\HoraireController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PlatController;
use App\Http\Controllers\Api\RegimeController;
use App\Http\Controllers\Api\ThemeController;
use App\Http\Controllers\Api\TypePlatController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::apiResource('/allergenes',AllergeneController::class)->only(['index']);
Route::apiResource('/plats',PlatController::class)->only(['index']);
Route::apiResource('/regimes',RegimeController::class)->only(['index']);
Route::apiResource('/themes',ThemeController::class)->only(['index']);
Route::apiResource('/menus',MenuController::class)->only(['index','show']);
Route::apiResource('/horaires',HoraireController::class)->only(['index']);
Route::apiResource('/avis', AvisController::class)->only(['index']);
Route::apiResource('/types-plats', TypePlatController::class)->only(['index']);
Route::get('/avis-all', [AvisController::class, 'all']);
Route::post('/chiffre-affaires/generate', [ChiffreAffaireController::class, 'generate']); 


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::apiResource('/avis', AvisController::class)->only(['store']);
    Route::apiResource('/commandes',CommandeController::class);

    Route::middleware( 'is_staff')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::apiResource('/allergenes',AllergeneController::class)->except(['index','show']);
        Route::apiResource('/plats',PlatController::class)->except(['index','show']);
        Route::apiResource('/regimes',RegimeController::class)->except(['index','show']);
        Route::apiResource('/themes',ThemeController::class)->except(['index','show']);
        Route::apiResource('/menus',MenuController::class)->except(['index','show']);
        Route::apiResource('/horaires',HoraireController::class)->except(['index']);
        Route::apiResource('/avis', AvisController::class)->except(['index','store','show']);
        Route::get('/roles', function () { return \App\Models\Role::all(); });
        Route::get('/staff', [UserController::class, 'getStaff']);
    });

    Route::middleware('is_admin')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::get('/chiffre-affaires', [ChiffreAffaireController::class, 'index']);    
    });
});