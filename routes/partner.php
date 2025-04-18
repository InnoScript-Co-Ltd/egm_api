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
        Route::post('/register', action: 'PartnerController@store');
        Route::post('/forgot-password', 'PartnerAuthController@forgotPassword');
        Route::post('/verified-otp', 'PartnerAuthController@verifiedOtp');
        Route::post('/reset-password', 'PartnerAuthController@resetPassword');

    });

    Route::middleware('jwt')->group(function () {

        Route::get('/status', 'PartnerStatusController@index');
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
            Route::post('/commission', 'PartnerReferralController@commissionReferralStore');
            Route::post('/level-four', 'PartnerReferralController@levelFourReferralStore');
            Route::get('/', 'PartnerReferralController@index');
            Route::get('/partner/{id}', 'PartnerReferralController@partnerIndex');
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::post('/', 'PartnerTransactionController@store');
            Route::get('/', 'PartnerTransactionController@index');
            Route::get('/{id}', 'PartnerTransactionController@show');
        });

        Route::group(['prefix' => 'agent'], function () {
            Route::get('/', 'PartnerAgentController@index');
        });

        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'PartnerDashboardController@index');
            Route::get('/{id}', 'PartnerDashboardController@show');

        });

        Route::group(['prefix' => 'banner'], function () {
            Route::get('/', 'PartnerBannerController@index');
            Route::get('/{id}', 'PartnerBannerController@show');
        });

        Route::group(['prefix' => 'wallet'], function () {

            Route::group(['prefix' => 'usdt'], function () {
                Route::post('/', 'PartnerUSDTAddressController@store');
                Route::get('/', 'PartnerUSDTAddressController@index');
                Route::get('/{id}', 'PartnerUSDTAddressController@show');
                Route::put('/{id}', 'PartnerUSDTAddressController@update');
                Route::delete('/{id}', 'PartnerUSDTAddressController@destory');
            });
        });
    });
});
