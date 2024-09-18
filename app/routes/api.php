<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\PostController;

Route::post('token', [ApiTokenController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('post', [PostController::class, 'test']);
});
