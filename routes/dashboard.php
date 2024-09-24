<?php

use App\Enums\PermissionEnum;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Article\ArticleLikeController;
use App\Http\Controllers\Article\ArticleTypeController;
use App\Http\Controllers\Article\CommentController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AdminAuthController@login');
});

Route::middleware('jwt')->group(function () {

    Route::group(['prefix' => 'status'], function () {
        Route::get('/', 'StatusController@index');
    });

    Route::group(['prefix' => 'count'], function () {
        Route::get('/order', 'DashboardController@orderCount');
        Route::get('/item', 'DashboardController@itemCount');
        Route::get('/user', 'DashboardController@userCount');
        Route::get('/', 'DashboardController@count');
    });

    Route::group(['prefix' => 'merchant-bank-account'], function () {
        Route::post('/', 'MerchantBankAccountController@store');
        Route::get('/', 'MerchantBankAccountController@index');
        Route::get('/{id}', 'MerchantBankAccountController@show');
        Route::put('/{id}', 'MerchantBankAccountController@update');
        Route::delete('/{id}', 'MerchantBankAccountController@destroy');
    });

    Route::group(['prefix' => 'partner'], function () {
        Route::get('/generate-password', 'PartnerController@generatePassword');
        Route::post('/', 'PartnerController@store');
        Route::get('/', 'PartnerController@index');
        Route::get('/{id}', 'PartnerController@show');
        Route::put('/{id}', 'PartnerController@update');
        Route::delete('/{id}', 'PartnerController@destroy');
    });

    Route::group(['prefix' => 'email-content'], function () {
        Route::post('/', 'EmailContentController@store')->permission(PermissionEnum::EMAIL_CONTENT_STORE->value);
        Route::get('/', 'EmailContentController@index')->permission(PermissionEnum::EMAIL_CONTENT_INDEX->value);
        Route::get('/{id}', 'EmailContentController@show')->permission(PermissionEnum::EMAIL_CONTENT_SHOW->value);
        Route::put('/{id}', 'EmailContentController@update')->permission(PermissionEnum::EMAIL_CONTENT_UPDATE->value);
        Route::delete('/{id}', 'EmailContentController@destroy')->permission(PermissionEnum::EMAIL_CONTENT_DESTROY->value);
    });

    Route::group(['prefix' => 'agent'], function () {
        Route::get('/', 'AgentController@index');
        Route::get('/{id}', 'AgentController@show');
        // Route::get('/', 'AgentController@index')->permission(PermissionEnum::AGENT_INDEX->value);;
        // Route::get('/{id}', 'AgentController@show')->permission(PermissionEnum::AGENT_SHOW->value);;
        Route::post('/', 'AgentController@store')->permission(PermissionEnum::AGENT_STORE->value);
        Route::post('/{id}', 'AgentController@update')->permission(PermissionEnum::AGENT_UPDATE->value);
        Route::delete('/{id}', 'AgentController@destroy')->permission(PermissionEnum::AGENT_DESTROY->value);
    });

    Route::group(['prefix' => 'sub-agent'], function () {
        Route::get('/', 'SubAgentController@index');
        Route::get('/{id}', 'SubAgentController@show');
        // Route::get('/', 'SubAgentController@index')->permission(PermissionEnum::Agent_INDEX->value);;
        // Route::get('/{id}', 'SubAgentController@show')->permission(PermissionEnum::Agent_SHOW->value);;
        Route::post('/', 'SubAgentController@store')->permission(PermissionEnum::SUB_AGENT_STORE->value);
        Route::post('/{id}', 'SubAgentController@update')->permission(PermissionEnum::SUB_AGENT_UPDATE->value);
        Route::delete('/{id}', 'SubAgentController@destroy')->permission(PermissionEnum::SUB_AGENT_DESTROY->value);
    });

    Route::group(['prefix' => 'agent-bank-account'], function () {
        Route::get('/', 'AgentBankAccountController@index')->permission(PermissionEnum::AGENT_BANK_ACCOUNT_INDEX->value);
        Route::post('/', 'AgentBankAccountController@store')->permission(PermissionEnum::AGENT_BANK_ACCOUNT_STORE->value);
        Route::get('/{id}', 'AgentBankAccountController@show')->permission(PermissionEnum::AGENT_BANK_ACCOUNT_SHOW->value);
        Route::put('/{id}', 'AgentBankAccountController@update')->permission(PermissionEnum::AGENT_BANK_ACCOUNT_UPDATE->value);
        Route::delete('/{id}', 'AgentBankAccountController@destroy')->permission(PermissionEnum::AGENT_BANK_ACCOUNT_DESTROY->value);
    });

    Route::group(['prefix' => 'package'], function () {
        Route::get('/', 'PackageController@index');
        Route::post('/', 'PackageController@store');
        Route::get('/{id}', 'PackageController@show');
        Route::put('/{id}', 'PackageController@update');
        // Route::get('/', 'PackageController@index')->permission(PermissionEnum::PACKAGE_INDEX->value);
        // Route::post('/', 'PackageController@store')->permission(PermissionEnum::PACKAGE_STORE->value);;
        // Route::get('/{id}', 'PackageController@show')->permission(PermissionEnum::PACKAGE_SHOW->value);;
        // Route::put('/{id}', 'PackageController@update')->permission(PermissionEnum::PACKAGE_UPDATE->value);;
        Route::delete('/{id}', 'PackageController@destroy')->permission(PermissionEnum::PACKAGE_DESTROY->value);
    });

    Route::group(['prefix' => 'deposit'], function () {
        Route::post('/', 'DepositController@store');
        Route::get('/', 'DepositController@index');
        Route::get('/{id}', 'DepositController@show');
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::post('/{id}/make-payment', 'TransactionController@makePayment');
        Route::get('/', 'TransactionController@index');
        Route::get('/{id}', 'TransactionController@show');
    });

    Route::group(['prefix' => 'permission'], function () {
        Route::get('/', 'PermissionController@index')->permission(PermissionEnum::PERMISSION_INDEX->value);
        Route::get('/{id}', 'PermissionController@show')->permission(PermissionEnum::PERMISSION_SHOW->value);
        Route::put('/{id}', 'PermissionController@update')->permission(PermissionEnum::PERMISSION_SHOW->value);
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

    Route::group(['prefix' => 'faq'], function () {
        Route::get('/', 'FaqController@index')->permission(PermissionEnum::FAQ_INDEX->value);
        Route::post('/', 'FaqController@store')->permission(PermissionEnum::FAQ_STORE->value);
        Route::get('/{id}', 'FaqController@show')->permission(PermissionEnum::FAQ_SHOW->value);
        Route::put('/{id}', 'FaqController@update')->permission(PermissionEnum::FAQ_UPDATE->value);
        Route::delete('/{id}', 'FaqController@destroy')->permission(PermissionEnum::FAQ_DESTROY->value);
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
            Route::get('/country/{id}', 'RegionAndStateController@countryBy')->permission(PermissionEnum::REGION_AND_STATE_INDEX->value);
            Route::post('/', 'RegionAndStateController@store')->permission(PermissionEnum::REGION_AND_STATE_STORE->value);
            Route::get('/{id}', 'RegionAndStateController@show')->permission(PermissionEnum::REGION_AND_STATE_SHOW->value);
            Route::put('/{id}', 'RegionAndStateController@update')->permission(PermissionEnum::REGION_AND_STATE_UPDATE->value);
            Route::delete('/{id}', 'RegionAndStateController@destroy')->permission(PermissionEnum::REGION_AND_STATE_DESTROY->value);
        });

        Route::group(['prefix' => 'city'], function () {
            Route::get('/', 'CityController@index')->permission(PermissionEnum::CITY_INDEX->value);
            Route::get('/region-or-state/{id}', 'CityController@cityByRegionOrState')->permission(PermissionEnum::CITY_INDEX->value);
            Route::post('/', 'CityController@store')->permission(PermissionEnum::CITY_STORE->value);
            Route::get('/{id}', 'CityController@show')->permission(PermissionEnum::CITY_SHOW->value);
            Route::put('/{id}', 'CityController@update')->permission(PermissionEnum::CITY_UPDATE->value);
            Route::delete('/{id}', 'CityController@destroy')->permission(PermissionEnum::CITY_DESTROY->value);
        });

        Route::group(['prefix' => 'township'], function () {
            Route::get('/', 'TownshipController@index')->permission(PermissionEnum::TOWNSHIP_INDEX->value);
            Route::get('/city/{id}', 'TownshipController@townshipByCity')->permission(PermissionEnum::TOWNSHIP_INDEX->value);
            Route::post('/', 'TownshipController@store')->permission(PermissionEnum::TOWNSHIP_STORE->value);
            Route::get('/{id}', 'TownshipController@show')->permission(PermissionEnum::TOWNSHIP_SHOW->value);
            Route::put('/{id}', 'TownshipController@update')->permission(PermissionEnum::TOWNSHIP_UPDATE->value);
            Route::delete('/{id}', 'TownshipController@destroy')->permission(PermissionEnum::TOWNSHIP_DESTROY->value);
        });
    });

    // article Type
    Route::group(['prefix' => 'article-type'], function () {
        Route::get('/', [ArticleTypeController::class, 'index'])->permission(PermissionEnum::ARTICLE_TYPE_INDEX->value);
        Route::post('/', [ArticleTypeController::class, 'store'])->permission(PermissionEnum::ARTICLE_TYPE_STORE->value);
        Route::get('/{id}', [ArticleTypeController::class, 'show'])->permission(PermissionEnum::ARTICLE_TYPE_SHOW->value);
        Route::put('/{id}', [ArticleTypeController::class, 'update'])->permission(PermissionEnum::ARTICLE_TYPE_UPDATE->value);
        Route::delete('/{id}', [ArticleTypeController::class, 'destroy'])->permission(PermissionEnum::ARTICLE_TYPE_DESTROY->value);
    });

    // article
    Route::group(['prefix' => 'article'], function () {
        Route::get('/', [ArticleController::class, 'index'])->permission(PermissionEnum::ARTICLE_INDEX->value);
        Route::post('/', [ArticleController::class, 'store'])->permission(PermissionEnum::ARTICLE_STORE->value);
        Route::get('/{id}', [ArticleController::class, 'show'])->permission(PermissionEnum::ARTICLE_SHOW->value);
        Route::put('/{id}', [ArticleController::class, 'update'])->permission(PermissionEnum::ARTICLE_UPDATE->value);
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->permission(PermissionEnum::ARTICLE_DESTROY->value);
    });

    // comment
    Route::group(['prefix' => 'comment'], function () {
        Route::get('/', [CommentController::class, 'index'])->permission(PermissionEnum::COMMENT_INDEX->value);
        Route::post('/', [CommentController::class, 'store'])->permission(PermissionEnum::COMMENT_STORE->value);
        Route::get('/{id}', [CommentController::class, 'show'])->permission(PermissionEnum::COMMENT_SHOW->value);
        Route::put('/{id}', [CommentController::class, 'update'])->permission(PermissionEnum::COMMENT_UPDATE->value);
        Route::delete('/{id}', [CommentController::class, 'destroy'])->permission(PermissionEnum::COMMENT_DESTROY->value);
    });

    // article like
    Route::group(['prefix' => 'article-like'], function () {
        Route::get('/', [ArticleLikeController::class, 'index'])->permission(PermissionEnum::ARTICLE_LIKE_INDEX->value);
        Route::post('/', [ArticleLikeController::class, 'store'])->permission(PermissionEnum::ARTICLE_LIKE_STORE->value);
        Route::get('/{id}', [ArticleLikeController::class, 'show'])->permission(PermissionEnum::ARTICLE_LIKE_SHOW->value);
        Route::delete('/{id}', [ArticleLikeController::class, 'destroy'])->permission(PermissionEnum::ARTICLE_LIKE_DESTROY->value);
    });

});
