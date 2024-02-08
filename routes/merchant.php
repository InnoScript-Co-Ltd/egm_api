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

Route::middleware(['merchant'])->group(function () {

    Route::group(['prefix' => 'member'], function () {
        Route::get('/', 'MemberController@index');
        Route::get('/{id}', 'MemberController@show');
    });

    Route::group(['prefix' => 'discount'], function () {
        Route::get('/{id}', 'MemberDiscountController@show');
    });

    Route::group(['prefix' => 'order'], function () {
        Route::post('/checkout', 'MembershipOrderController@checkout');
        Route::get('/', 'MembershipOrderController@index');
        Route::get('/{id}', 'MembershipOrderController@show');
    });

    Route::group(['prefix' => 'count'], function () {
        Route::get('/', 'DashboardController@count');
    });
});
