<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\apiController;

Route::get('/check-database', [apiController::class, 'checkDatabase']);
Route::post('/create-order', [ApiController::class, 'createOrder']);
