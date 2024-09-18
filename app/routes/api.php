<?php

use App\Http\Controllers\ApiLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\PostController;

//Route::post('token', [ApiTokenController::class, 'token']);

//Route::get('post', [PostController::class, 'test']);

/*
Route::middleware('auth:sanctum')->group(function () {
   // Route::get('post', [PostController::class, 'test']);
});
*/

Route::post('login', [ApiLoginController::class, 'login'])
    ->name('api_login');


Route::middleware('auth:sanctum')->group(function() {
    Route::get('post', [PostController::class, 'test']);
});

