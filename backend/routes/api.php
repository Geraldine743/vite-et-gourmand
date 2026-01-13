<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/bonjour', function () {
    return ['message' => 'Bravo ! Votre API fonctionne a merveille.'];
});