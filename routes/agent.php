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

    Route::group(['prefix' => 'main'], function () {
        Route::post('/register', 'MainAgentController@store');
    });

    Route::group(['prefix' => 'sub'], function () {
        Route::post('/register', 'SubAgentController@store');
    });

    Route::post('/verify', 'AccountController@emailVerify');
    Route::post('resend', 'AccountController@resendVerifyCode');
    Route::post('/auth/login', 'AgentAuthController@login');

    Route::middleware('jwt')->group(function () {

        Route::get('/level/{level}', 'SubAgentController@level');

        Route::group(['prefix' => 'account'], function () {
            Route::post('/{id}', 'AccountController@update');
            Route::post('/{id}/kyc-update', 'AccountController@kycUpdate');
            Route::post('/{id}/account-update', 'AccountController@accountUpdate');
        });

        Route::group(['prefix' => 'auth'], function () {
            Route::post('/change-password', 'AgentAuthController@changePassword');
            Route::post('/payment-password', 'AgentAuthController@updatePaymentPassword');
            Route::get('/profile', 'AgentAuthController@profile');
        });

        Route::group(['prefix' => 'main'], function () {
            Route::get('/reference-link', 'MainAgentController@referenceLink');
        });

        Route::group(['prefix' => 'sub'], function () {
            Route::get('/reference-link', 'SubAgentController@referenceLink');
        });

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
            Route::get('/', 'AgentPackageController@index');
            Route::get('/{id}', 'AgentPackageController@show');
            Route::post('/', 'AgentPackageController@store');
        });

        Route::group(['prefix' => 'investor-package'], function () {
            Route::get('/', 'InvestorPackageController@index');
            Route::post('/', 'InvestorPackageController@store');
        });

    });
});
