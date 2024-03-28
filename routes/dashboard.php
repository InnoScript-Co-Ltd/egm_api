<?php

use App\Enums\PermissionEnum;
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

Route::get('/image/{name}', 'ImageController@show');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AdminAuthController@login');
});

Route::middleware('jwt')->group(function () {

    // Route::group(['prefix' => 'auth'], function () {
    //     Route::post('/logout', [AdminAuthController::class, 'logout']);
    //     Route::post('/refresh', [AdminAuthController::class, 'refresh']);
    // });
    Route::post('/export-shop', 'ShopController@export');
    Route::post('/export-order', 'OrderController@export');
    Route::post('/export-item', 'ItemController@export');
    Route::post('/import-item', 'ItemController@import');
    Route::post('/export-user', 'UserController@export');
    Route::post('/export-category', 'CategoryController@export');
    Route::post('/import-category', 'CategoryController@import');

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

    Route::group(['prefix' => 'member'], function () {
        Route::get('/next', 'MemberController@nextMemberId')->permission(PermissionEnum::MEMBER_ID_NEXT->value);
        Route::get('/', 'MemberController@index')->permission(PermissionEnum::MEMBER_INDEX->value);
        Route::post('/', 'MemberController@store')->permission(PermissionEnum::MEMBER_STORE->value);
        Route::get('/{id}', 'MemberController@show')->permission(PermissionEnum::MEMBER_SHOW->value);
        Route::put('/{id}', 'MemberController@update')->permission(PermissionEnum::MEMBER_UPDATE->value);
        Route::delete('/{id}', 'MemberController@destroy')->permission(PermissionEnum::MEMBER_DESTROY->value);
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
        Route::post('/{id}/assign-role', 'RoleController@assignRole')->permission(PermissionEnum::ROLE_ASSIGN->value);
        Route::put('/{id}/remove-permission', 'RoleController@removePermission')->permission(PermissionEnum::ROLE_PERMISSION_REMOVE->value);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index')->permission(PermissionEnum::USER_INDEX->value);
        Route::post('/', 'UserController@store')->permission(PermissionEnum::USER_STORE->value);
        Route::get('/{id}', 'UserController@show')->permission(PermissionEnum::USER_SHOW->value);
        Route::post('/{id}', 'UserController@update')->permission(PermissionEnum::USER_UPDATE->value);
        Route::delete('/{id}', 'UserController@destroy')->permission(PermissionEnum::USER_DESTROY->value);
        Route::get('/export', 'UserController@export')->permission(PermissionEnum::USER_EXPORT->value);
    });

    Route::group(['prefix' => 'admin'], function () {

        Route::get('/', 'AdminController@index')->permission(PermissionEnum::ADMIN_INDEX->value);
        Route::post('/', 'AdminController@store')->permission(PermissionEnum::ADMIN_STORE->value);
        Route::get('/{id}', 'AdminController@show')->permission(PermissionEnum::ADMIN_SHOW->value);
        Route::post('/{id}', 'AdminController@update')->permission(PermissionEnum::ADMIN_UPDATE->value);
        Route::delete('/{id}', 'AdminController@destroy')->permission(PermissionEnum::ADMIN_DESTROY->value);

    });

    Route::group(['prefix' => 'category'], function () {

        Route::get('/', 'CategoryController@index')->permission(PermissionEnum::CATEGORY_INDEX->value);
        Route::post('/', 'CategoryController@store')->permission(PermissionEnum::CATEGORY_STORE->value);
        Route::get('/{id}', 'CategoryController@show')->permission(PermissionEnum::CATEGORY_SHOW->value);
        Route::post('/{id}', 'CategoryController@update')->permission(PermissionEnum::CATEGORY_UPDATE->value);
        Route::delete('/{id}', 'CategoryController@destroy')->permission(PermissionEnum::CATEGORY_DESTROY->value);

    });

    Route::group(['prefix' => 'item'], function () {

        Route::get('/', 'ItemController@index')->permission(PermissionEnum::ITEM_INDEX->value);
        Route::post('/', 'ItemController@store')->permission(PermissionEnum::ITEM_STORE->value);
        Route::get('/{id}', 'ItemController@show')->permission(PermissionEnum::ITEM_SHOW->value);
        Route::post('/{id}', 'ItemController@update')->permission(PermissionEnum::ITEM_UPDATE->value);
        Route::delete('/{id}', 'ItemController@destroy')->permission(PermissionEnum::ITEM_DESTROY->value);

    });

    Route::group(['prefix' => 'promotion'], function () {
        Route::get('/', 'PromotionController@index')->permission(PermissionEnum::PROMOTION_INDEX->value);
        Route::post('/', 'PromotionController@store')->permission(PermissionEnum::PROMOTION_STORE->value);
        Route::get('/{id}', 'PromotionController@show')->permission(PermissionEnum::PROMOTION_SHOW->value);
        Route::post('/{id}', 'PromotionController@update')->permission(PermissionEnum::PROMOTION_UPDATE->value);
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
        Route::post('/{id}', 'ShopController@update')->permission(PermissionEnum::SHOP_UPDATE->value);
        Route::delete('/{id}', 'ShopController@destroy')->permission(PermissionEnum::SHOP_DESTROY->value);
    });

    Route::group(['prefix' => 'member-discount'], function () {
        Route::get('/', 'MemberDiscountController@index')->permission(PermissionEnum::MEMBER_DISCOUNT_INDEX->value);
        Route::post('/', 'MemberDiscountController@store')->permission(PermissionEnum::MEMBER_DISCOUNT_STORE->value);
        Route::get('/{id}', 'MemberDiscountController@show')->permission(PermissionEnum::MEMBER_DISCOUNT_SHOW->value);
        Route::put('/{id}', 'MemberDiscountController@update')->permission(PermissionEnum::MEMBER_DISCOUNT_UPDATE->value);
        Route::delete('/{id}', 'MemberDiscountController@destroy')->permission(PermissionEnum::MEMBER_DISCOUNT_DESTROY->value);
    });

    Route::group(['prefix' => 'member-card'], function () {
        Route::get('/', 'MemberCardController@index')->permission(PermissionEnum::MEMBER_CARD_INDEX->value);
        Route::post('/', 'MemberCardController@store')->permission(PermissionEnum::MEMBER_CARD_STORE->value);
        Route::get('/{id}', 'MemberCardController@show')->permission(PermissionEnum::MEMBER_CARD_SHOW->value);
        Route::put('/{id}', 'MemberCardController@update')->permission(PermissionEnum::MEMBER_CARD_UPDATE->value);
        Route::delete('/{id}', 'MemberCardController@destroy')->permission(PermissionEnum::MEMBER_CARD_DESTROY->value);
    });

    Route::group(['prefix' => 'member-order'], function () {
        Route::get('/', 'MembershipOrderController@index')->permission(PermissionEnum::MEMBER_ORDER_INDEX->value);
        Route::get('/{id}', 'MembershipOrderController@show')->permission(PermissionEnum::MEMBER_ORDER_SHOW->value);
    });

    Route::group(['prefix' => 'location'], function () {
        Route::group(['prefix' => 'country'], function () {
            Route::get('/', 'CountryController@index')->permission(PermissionEnum::COUNTRY_INDEX->value);
            Route::post('/', 'CountryController@store')->permission(PermissionEnum::COUNTRY_STORE->value);
            Route::get('/{id}', 'CountryController@show')->permission(PermissionEnum::COUNTRY_SHOW->value);
            Route::post('/{id}', 'CountryController@update')->permission(PermissionEnum::COUNTRY_UPDATE->value);
            Route::delete('/{id}', 'CountryController@destroy')->permission(PermissionEnum::COUNTRY_DESTROY->value);
        });

        Route::group(['prefix' => 'region-and-state'], function () {
            Route::get('/', 'RegionAndStateController@index')->permission(PermissionEnum::REGION_AND_STATE_INDEX->value);
            Route::post('/', 'RegionAndStateController@store')->permission(PermissionEnum::REGION_AND_STATE_STORE->value);
            Route::get('/{id}', 'RegionAndStateController@show')->permission(PermissionEnum::REGION_AND_STATE_SHOW->value);
            Route::put('/{id}', 'RegionAndStateController@update')->permission(PermissionEnum::REGION_AND_STATE_UPDATE->value);
            Route::delete('/{id}', 'RegionAndStateController@destroy')->permission(PermissionEnum::REGION_AND_STATE_DESTROY->value);
        });

        Route::group(['prefix' => 'city'], function () {
            Route::get('/', 'CityController@index')->permission(PermissionEnum::CITY_INDEX->value);
            Route::post('/', 'CityController@store')->permission(PermissionEnum::CITY_STORE->value);
            Route::get('/{id}', 'CityController@show')->permission(PermissionEnum::CITY_SHOW->value);
            Route::put('/{id}', 'CityController@update')->permission(PermissionEnum::CITY_UPDATE->value);
            Route::delete('/{id}', 'CityController@destroy')->permission(PermissionEnum::CITY_DESTROY->value);
        });

        Route::group(['prefix' => 'township'], function () {
            Route::get('/', 'TownshipController@index')->permission(PermissionEnum::TOWNSHIP_INDEX->value);
            Route::post('/', 'TownshipController@store')->permission(PermissionEnum::TOWNSHIP_STORE->value);
            Route::get('/{id}', 'TownshipController@show')->permission(PermissionEnum::TOWNSHIP_SHOW->value);
            Route::put('/{id}', 'TownshipController@update')->permission(PermissionEnum::TOWNSHIP_UPDATE->value);
            Route::delete('/{id}', 'TownshipController@destroy')->permission(PermissionEnum::TOWNSHIP_DESTROY->value);
        });
    });

    Route::group(['prefix' => 'mpe'], function () {
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', 'MPECategoryController@index');
            Route::post('/', 'MPECategoryController@store');
            Route::get('/{id}', 'MPECategoryController@show');
            Route::put('/{id}', 'MPECategoryController@update');
            Route::delete('/{id}', 'MPECategoryController@destory');
        });

        Route::group(['prefix' => 'units'], function () {
            Route::get('/', 'MPEUnitController@index');
            Route::post('/', 'MPEUnitController@store');
            Route::get('/{id}', 'MPEUnitController@show');
            Route::put('/{id}', 'MPEUnitController@update');
            Route::delete('/{id}', 'MPEUnitController@destory');
        });

        Route::group(['prefix' => 'item'], function () {
            Route::get('/', 'MPEItemController@index');
            Route::post('/', 'MPEItemController@store');
            Route::get('/{id}', 'MPEItemController@show');
            Route::put('/{id}', 'MPEItemController@update');
            Route::delete('/{id}', 'MPEItemController@destory');
        });

    });

    Route::post('/file/upload/image', 'FileController@store');

});
