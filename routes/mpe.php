<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['mpe'])->group(function () {
    Route::post('/register', 'MPEUserController@register');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'MPEUserController@login');
    });
});
