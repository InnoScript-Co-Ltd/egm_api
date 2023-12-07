<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeliveryAddressController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Enums\PermissionEnum;

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

Route::get('/media/{id}', 'FileController@show');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AdminAuthController@login');
});

Route::get('/export-category', 'CategoryController@export');
Route::get('/export-user', 'UserController@export');
Route::get('/export-item', 'ItemController@export');
Route::get('/export-order', 'OrderController@export');
Route::get('/export-shop', 'ShopController@export');

Route::middleware('jwt')->group(function () {

    // Route::group(['prefix' => 'auth'], function () {
    //     Route::post('/logout', [AdminAuthController::class, 'logout']);
    //     Route::post('/refresh', [AdminAuthController::class, 'refresh']);
    // });

    Route::get('/media', 'FileController@index');

    Route::group(['prefix' => 'status'], function () {
        Route::get('/', [StatusController::class, 'index']);
    });

    Route::group(['prefix' => 'count'], function () {
        Route::get('/order', 'DashboardController@orderCount');
        Route::get('/item', 'DashboardController@itemCount');
        Route::get('/user', 'DashboardController@userCount');
        Route::get('/', 'DashboardController@count');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->permission(PermissionEnum::USER_INDEX->value);
        Route::post('/', 'UserController@store')->permission(PermissionEnum::USER_STORE->value);
        Route::get('/{id}', 'UserController@show')->permission(PermissionEnum::USER_SHOW->value);
        Route::put('/{id}', 'UserController@update')->permission(PermissionEnum::USER_UPDATE->value);
        Route::delete('/{id}', 'UserController@destroy')->permission(PermissionEnum::USER_DESTROY->value);
        Route::get('/export', 'UserController@export');
    });

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/', 'AdminController@index');
        Route::post('/', 'AdminController@store');
        Route::get('/{id}', 'AdminController@show');
        Route::put('/{id}', 'AdminController@update');
        Route::delete('/{id}', 'AdminController@destroy');

    });

    Route::group(['prefix' => 'category'], function () {

        Route::get('/', 'CategoryController@index');
        Route::post('/', 'CategoryController@store');
        Route::get('/{id}', 'CategoryController@show');
        Route::put('/{id}', 'CategoryController@update');
        Route::delete('/{id}', 'CategoryController@destroy');

    });

    Route::group(['prefix' => 'item'], function () {

        Route::get('/', 'ItemController@index');
        Route::post('/', 'ItemController@store');
        Route::get('/{id}', 'ItemController@show');
        Route::put('/{id}', 'ItemController@update');
        Route::delete('/{id}', 'ItemController@destroy');

    });

    Route::group(['prefix' => 'promotion'], function () {

        Route::get('/', 'PromotionController@index');
        Route::post('/', 'PromotionController@store');
        Route::get('/{id}', 'PromotionController@show');
        Route::put('/{id}', 'PromotionController@update');
        Route::delete('/{id}', 'PromotionController@destory');

    });

    Route::group(['prefix' => 'delivery-address'], function () {

        Route::get('/', 'DeliveryAddressController@index');
        Route::post('/', 'DeliveryAddressController@store');
        Route::get('/{id}', 'DeliveryAddressController@show');
        Route::put('/{id}', 'DeliveryAddressController@update');
        Route::delete('/{id}', 'DeliveryAddressController@destroy');

    });

    Route::group(['prefix' => 'order'], function () {

        Route::get('/', 'OrderController@index');
        Route::post('/', 'OrderController@store');
        Route::get('/{id}', 'OrderController@show');
        Route::put('/{id}', 'OrderController@update');
        Route::delete('/{id}', 'OrderController@destroy');

    });

    Route::group(['prefix' => 'point'], function () {
        Route::get('/', 'PointController@index');
        Route::post('/', 'PointController@store');
        Route::get('/{id}', 'PointController@show');
        Route::put('/{id}', 'PointController@update');
        Route::delete('/{id}', 'PointController@destroy');
    });

    Route::group(['prefix' => 'faq'], function () {
        Route::get('/', 'FaqController@index');
        Route::post('/', 'FaqController@store');
        Route::get('/{id}', 'FaqController@show');
        Route::put('/{id}', 'FaqController@update');
        Route::delete('/{id}', 'FaqController@destroy');
    });

    Route::group(['prefix' => 'region'], function () {
        Route::get('/', 'RegionController@index');
        Route::post('/', 'RegionController@store');
        Route::get('/{id}', 'RegionController@show');
        Route::put('/{id}', 'RegionController@update');
        Route::delete('/{id}', 'RegionController@destroy');
    });

    Route::group(['prefix' => 'shop'], function () {
        Route::get('/', 'ShopController@index');
        Route::post('/', 'ShopController@store');
        Route::get('/{id}', 'ShopController@show');
        Route::put('/{id}', 'ShopController@update');
        Route::delete('/{id}', 'ShopController@destroy');
    });

    Route::post('/file/upload/image', 'FileController@store');
});
