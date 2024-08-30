<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main', [
        'phone' => config('top.phone'),
    ]);
});

Route::get('/about', function () {
    return view('about', [
        'phone' => config('top.phone'),
    ]);
});

Route::get('/welcome', function () {
    return view('welcome');
});
