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

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/verify', 'AccountController@emailVerify');
        Route::post('resend', 'AccountController@resendVerifyCode');
        Route::post('/login', 'AgentAuthController@login');
    });

    Route::group(['prefix' => 'account'], function () {
        Route::post('/create', 'AccountController@store');
    });

    Route::get('/level/{level}', 'SubAgentController@level');
    Route::get('/profile/{id}', 'AgentController@show');

    Route::middleware('jwt')->group(function () {

        Route::group(['prefix' => 'account'], function () {
            Route::get('/referral', 'AccountController@generateLink');
            Route::post('/', 'AccountController@update');
            Route::post('/kyc', 'AccountController@kycUpdate');
            Route::post('/account-update', 'AccountController@accountUpdate');
        });

        Route::group(['prefix' => 'auth'], function () {
            Route::post('/change-password', 'AgentAuthController@changePassword');
            Route::post('/payment-password', 'AgentAuthController@updatePaymentPassword');
            Route::post('/payment-password/check', 'AgentAuthController@confirmPaymentPassword');
            Route::get('/profile', 'AgentAuthController@profile');
        });

        Route::group(['prefix' => 'package'], function () {
            Route::get('/', 'PackageController@index');
            Route::get('/{id}', 'PackageController@show');
        });

        Route::group(['prefix' => 'deposit'], function () {
            Route::post('/', 'AgentDepositController@store');
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('/', 'AgentTransactionController@index');
            Route::get('/{id}', 'AgentTransactionController@show');
        });

        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'DashboardController@index');
        });

        Route::group(['prefix' => 'agent-bank-account'], function () {
            Route::get('/', 'AgentBankAccountController@index');
            Route::post('/', 'AgentBankAccountController@store');
            Route::put('/{id}', 'AgentBankAccountController@update');
            Route::delete('/{id}', 'AgentBankAccountController@destroy');
        });

        Route::group(['prefix' => 'merchant-bank-account'], function () {
            Route::get('/', 'MerchantBankAccountController@index');
        });

        Route::group(['prefix' => 'referral'], function () {
            Route::get('/', 'AgentReferralController@index');
            Route::post('/', 'AgentReferralController@store');
        });
    });
});
