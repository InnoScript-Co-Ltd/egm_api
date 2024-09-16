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

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'PartnerAuthController@login');
    });

    Route::middleware('jwt')->group(function () {

        Route::get('/status', 'StatusController@index');
        Route::get('/deposit-package', 'PartnerDepositPackageController@index');
        Route::get('/merchant-bank-account', 'PartnerMerchantBankAccountController@index');

        Route::group(['prefix' => 'auth'], function () {
            Route::get('/profile', 'PartnerAuthController@profile');
            Route::post('/change-password', 'PartnerAuthController@changePassword');
            Route::post('/payment-password', 'PartnerAuthController@updatePaymentPassword');
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

        Route::group(['prefix' => 'referral'], function () {
            Route::post('/', 'PartnerReferralController@store');
            Route::get('/', 'PartnerReferralController@index');
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::post('/', 'PartnerTransactionController@store');
            Route::get('/', 'PartnerTransactionController@index');
        });

        Route::group(['prefix' => 'agent'], function () {
            Route::get('/', 'PartnerAgentController@index');
        });

        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'PartnerDashboardController@index');
        });
    });
});
