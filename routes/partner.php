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

Route::middleware(['partner'])->group(function () {

    Route::get('/status', 'StatusController@index');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'PartnerAuthController@login');
        Route::post('/change-password', 'PartnerAuthController@changePassword');
        Route::post('/payment-password', 'PartnerAuthController@updatePaymentPassword');
    });

    Route::post('/reference-link', 'PartnerController@referenceLink');

    Route::middleware('jwt')->group(function () {

        Route::group(['prefix' => 'auth'], function () {
            Route::get('/profile', 'PartnerAuthController@profile');
            Route::put('/profile', 'PartnerController@updateInfo');
        });

        Route::group(['prefix' => 'account'], function () {
            Route::put('/', 'PartnerController@updateAccount');
            Route::put('/info', 'PartnerController@updateInfo');
            Route::post('/kyc', 'PartnerController@updateKYC');
        });

        Route::group(['prefix' => 'bank-account'], function () {
            Route::post('/', 'PartnerBankAccountController@store');
            Route::get('/', 'PartnerBankAccountController@index');
            Route::get('/{id}', 'PartnerBankAccountController@show');
            Route::put('/{id}', 'PartnerBankAccountController@update');
        });

        Route::get('/status', 'StatusController@index');

        // Route::group(['prefix' => 'package'], function () {
        //     Route::get('/', 'PackageController@index');
        //     Route::get('/{id}', 'PackageController@show');
        // });

        // Route::group(['prefix' => 'bank-account'], function () {
        //     Route::get('/', 'BankAccountController@index');
        //     Route::post('/', 'BankAccountController@store');
        // });

        // Route::group(['prefix' => 'investor'], function () {
        //     Route::get('/', 'InvestorController@index');
        //     Route::get('/{id}', 'InvestorController@show');
        //     Route::post('/', 'InvestorController@store');
        //     Route::post('/verify-code', 'InvestorController@verifyCode');
        //     Route::post('/resend-code', 'InvestorController@resendCode');
        // });

        // Route::group(['prefix' => 'agent-package'], function () {
        //     Route::get('/', 'AgentPackageController@index');
        //     Route::get('/{id}', 'AgentPackageController@show');
        //     Route::post('/', 'AgentPackageController@store');
        // });

        // Route::group(['prefix' => 'investor-package'], function () {
        //     Route::get('/', 'InvestorPackageController@index');
        //     Route::post('/', 'InvestorPackageController@store');
        // });

        // Route::group(['prefix' => 'channel'], function () {
        //     Route::get('/', 'AgentChannelController@index');
        //     Route::post('/', 'AgentChannelController@store');
        //     Route::get('/{id}', 'AgentChannelController@show');
        //     Route::put('/{id}', 'AgentChannelController@update');
        //     Route::delete('/{id}', 'AgentChannelController@destroy');
        // });

        // Route::group(['prefix' => 'agent-in-channel'], function () {
        //     Route::get('/', 'AgentInChannelController@index');
        //     Route::post('/', 'AgentInChannelController@store');
        //     Route::put('/{id}', 'AgentInChannelController@update');
        //     Route::delete('/{id}', 'AgentInChannelController@destroy');
        // });
    });
});
