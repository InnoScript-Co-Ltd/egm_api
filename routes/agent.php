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

Route::middleware(['agent'])->group(function () {

    Route::post('/register', 'AccountController@store');
    Route::post('verification', 'AccountController@emailVerify');
    Route::post('verification-code', 'AccountController@resendVerifyCode');

    Route::post('/auth/login', 'AgentAuthController@login');

    Route::middleware('jwt')->group(function () {
        Route::get('/auth/profile', 'AgentAuthController@profile');

        Route::group(['prefix' => 'package'], function () {
            Route::get('/', 'PackageController@index');
            Route::get('/{id}', 'PackageController@show');
        });

        Route::group(['prefix' => 'bank-account'], function () {
            Route::get('/', 'BankAccountController@index');
            Route::post('/', 'BankAccountController@store');
        });

        Route::group(['prefix' => 'investor'], function () {
            Route::get('/', 'InvestorController@index');
            Route::get('/{id}', 'InvestorController@show');
            Route::post('/', 'InvestorController@store');
            Route::post('/verify-code', 'InvestorController@verifyCode');
            Route::post('/resend-code', 'InvestorController@resendCode');
        });

        Route::group(['prefix' => 'agent-package'], function () {
            Route::post('/', 'AgentPackageController@store');
        });

        Route::group(['prefix' => 'investor-package'], function () {
            Route::post('/', 'InvestorPackageController@store');
        });
    });
});
