<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StatusController;
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
        Route::get('/', 'StatusController@index');
    });

    Route::group(['prefix' => 'count'], function () {
        Route::get('/order', 'DashboardController@orderCount');
        Route::get('/item', 'DashboardController@itemCount');
        Route::get('/user', 'DashboardController@userCount');
        Route::get('/', 'DashboardController@count');
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', 'PermissionController@index')->permission(PermissionEnum::PERMISSION_INDEX->value);
        Route::get('/{id}', 'PermissionController@show')->permission(PermissionEnum::PERMISSION_SHOW->value);
    });

    Route::group(['prefix' => 'role'], function () {
        Route::get('/', 'RoleController@index')->permission(PermissionEnum::PERMISSION_INDEX->value);
        Route::post('/', 'RoleController@store')->permission(PermissionEnum::ROLE_STORE->value);
        Route::put('/{id}', 'RoleController@update')->permission(PermissionEnum::ROLE_UPDATE->value);
        Route::get('/{id}', 'RoleController@show')->permission(PermissionEnum::ROLE_SHOW->value);
        Route::post('/{id}/remove-permission', 'RoleController@removePermission');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->permission(PermissionEnum::USER_INDEX->value);
        Route::post('/', 'UserController@store')->permission(PermissionEnum::USER_STORE->value);
        Route::get('/{id}', 'UserController@show')->permission(PermissionEnum::USER_SHOW->value);
        Route::put('/{id}', 'UserController@update')->permission(PermissionEnum::USER_UPDATE->value);
        Route::delete('/{id}', 'UserController@destroy')->permission(PermissionEnum::USER_DESTROY->value);
        Route::get('/export', 'UserController@export')->permission(PermissionEnum::USER_EXPORT->value);
    });

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/', 'AdminController@index')->permission(PermissionEnum::ADMIN_INDEX->value);
        Route::post('/', 'AdminController@store')->permission(PermissionEnum::ADMIN_STORE->value);
        Route::get('/{id}', 'AdminController@show')->permission(PermissionEnum::ADMIN_SHOW->value);
        Route::put('/{id}', 'AdminController@update')->permission(PermissionEnum::ADMIN_UPDATE->value);
        Route::delete('/{id}', 'AdminController@destroy')->permission(PermissionEnum::ADMIN_DESTROY->value);

    });

    Route::group(['prefix' => 'category'], function () {

        Route::get('/', 'CategoryController@index')->permission(PermissionEnum::CATEGORY_INDEX->value);
        Route::post('/', 'CategoryController@store')->permission(PermissionEnum::CATEGORY_STORE->value);
        Route::get('/{id}', 'CategoryController@show')->permission(PermissionEnum::CATEGORY_SHOW->value);
        Route::put('/{id}', 'CategoryController@update')->permission(PermissionEnum::CATEGORY_UPDATE->value);
        Route::delete('/{id}', 'CategoryController@destroy')->permission(PermissionEnum::CATEGORY_DESTROY->value);

    });

    Route::group(['prefix' => 'item'], function () {

        Route::get('/', 'ItemController@index')->permission(PermissionEnum::ITEM_INDEX->value);
        Route::post('/', 'ItemController@store')->permission(PermissionEnum::ITEM_STORE->value);
        Route::get('/{id}', 'ItemController@show')->permission(PermissionEnum::ITEM_SHOW->value);
        Route::put('/{id}', 'ItemController@update')->permission(PermissionEnum::ITEM_UPDATE->value);
        Route::delete('/{id}', 'ItemController@destroy')->permission(PermissionEnum::ITEM_DESTROY->value);

    });

    Route::group(['prefix' => 'promotion'], function () {

        Route::get('/', 'PromotionController@index')->permission(PermissionEnum::PROMOTION_INDEX->value);
        Route::post('/', 'PromotionController@store')->permission(PermissionEnum::PROMOTION_STORE->value);
        Route::get('/{id}', 'PromotionController@show')->permission(PermissionEnum::PROMOTION_SHOW->value);
        Route::put('/{id}', 'PromotionController@update')->permission(PermissionEnum::PROMOTION_UPDATE->value);
        Route::delete('/{id}', 'PromotionController@destory')->permission(PermissionEnum::PROMOTION_DESTROY->value);

    });

    Route::group(['prefix' => 'delivery-address'], function () {

        Route::get('/', 'DeliveryAddressController@index')->permission(PermissionEnum::DELIVERY_ADDRESS_INDEX->value);
        Route::post('/', 'DeliveryAddressController@store')->permission(PermissionEnum::DELIVERY_ADDRESS_STORE->value);
        Route::get('/{id}', 'DeliveryAddressController@show')->permission(PermissionEnum::DELIVERY_ADDRESS_SHOW->value);
        Route::put('/{id}', 'DeliveryAddressController@update')->permission(PermissionEnum::DELIVERY_ADDRESS_UPDATE->value);
        Route::delete('/{id}', 'DeliveryAddressController@destroy')->permission(PermissionEnum::DELIVERY_ADDRESS_DESTROY->value);

    });

    Route::group(['prefix' => 'order'], function () {

        Route::get('/', 'OrderController@index')->permission(PermissionEnum::ORDER_INDEX->value);
        Route::post('/', 'OrderController@store')->permission(PermissionEnum::ORDER_STORE->value);
        Route::get('/{id}', 'OrderController@show')->permission(PermissionEnum::ORDER_SHOW->value);
        Route::put('/{id}', 'OrderController@update')->permission(PermissionEnum::ORDER_UPDATE->value);
        Route::delete('/{id}', 'OrderController@destroy')->permission(PermissionEnum::ORDER_DESTROY->value);

    });

    Route::group(['prefix' => 'point'], function () {
        Route::get('/', 'PointController@index')->permission(PermissionEnum::POINT_INDEX->value);
        Route::post('/', 'PointController@store')->permission(PermissionEnum::POINT_STORE->value);
        Route::get('/{id}', 'PointController@show')->permission(PermissionEnum::POINT_SHOW->value);
        Route::put('/{id}', 'PointController@update')->permission(PermissionEnum::POINT_UPDATE->value);
        Route::delete('/{id}', 'PointController@destroy')->permission(PermissionEnum::POINT_DESTROY->value);
    });

    Route::group(['prefix' => 'faq'], function () {
        Route::get('/', 'FaqController@index')->permission(PermissionEnum::FAQ_INDEX->value);
        Route::post('/', 'FaqController@store')->permission(PermissionEnum::FAQ_STORE->value);
        Route::get('/{id}', 'FaqController@show')->permission(PermissionEnum::FAQ_SHOW->value);
        Route::put('/{id}', 'FaqController@update')->permission(PermissionEnum::FAQ_UPDATE->value);
        Route::delete('/{id}', 'FaqController@destroy')->permission(PermissionEnum::FAQ_DESTROY->value);
    });

    Route::group(['prefix' => 'region'], function () {
        Route::get('/', 'RegionController@index')->permission(PermissionEnum::REGION_INDEX->value);
        Route::post('/', 'RegionController@store')->permission(PermissionEnum::REGION_STORE->value);
        Route::get('/{id}', 'RegionController@show')->permission(PermissionEnum::REGION_SHOW->value);
        Route::put('/{id}', 'RegionController@update')->permission(PermissionEnum::REGION_UPDATE->value);
        Route::delete('/{id}', 'RegionController@destroy')->permission(PermissionEnum::REGION_DESTROY->value);
    });

    Route::group(['prefix' => 'shop'], function () {
        Route::get('/', 'ShopController@index')->permission(PermissionEnum::SHOP_INDEX->value);
        Route::post('/', 'ShopController@store')->permission(PermissionEnum::SHOP_STORE->value);
        Route::get('/{id}', 'ShopController@show')->permission(PermissionEnum::SHOP_SHOW->value);
        Route::put('/{id}', 'ShopController@update')->permission(PermissionEnum::SHOP_UPDATE->value);
        Route::delete('/{id}', 'ShopController@destroy')->permission(PermissionEnum::SHOP_DESTROY->value);
    });

    Route::post('/file/upload/image', 'FileController@store');
});
