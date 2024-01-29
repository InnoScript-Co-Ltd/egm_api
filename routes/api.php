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

// Route::group(['prefix' => 'auth'], function () {
//     Route::post('/login', 'WebAuthController@login');
//     Route::get('/logout', 'WebAuthController@logout');
//     Route::get('/refresh', 'WebAuthController@refresh');
//     Route::post('/resend/{type}', 'UserAuthController@resendVerifiedCode');
// });

Route::middleware('jwt')->group(function () {

    // Route::group(['prefix' => 'item'], function () {
    //     Route::get('/', 'WebItemController@index');
    // });

    // Route::group(['prefix' => 'order'], function () {
    //     Route::get('/', 'WebOrderController@index');
    // });

    // Route::group(['prefix' => 'auth'], function () {
    //     Route::post('/logout', [UserAuthController::class, 'logout']);
    //     Route::post('/refresh', [UserAuthController::class, 'refresh']);
    // });

    // Route::group(['prefix' => 'user'], function () {
    //     Route::get('/', [UserController::class, 'index']);
    //     Route::post('/', [UserController::class, 'store']);
    //     Route::get('/{id}', [UserController::class, 'show']);
    //     Route::put('/{id}', [UserController::class, 'update']);
    //     Route::delete('/{id}', [UserController::class, 'delete']);
    // });

    // Route::group(['prefix' => 'admin'], function () {

    //     Route::get('/', [AdminController::class, 'index']);
    //     Route::post('/', [AdminController::class, 'store']);
    //     Route::get('/{id}', [AdminController::class, 'show']);
    //     Route::put('/{id}', [AdminController::class, 'update']);
    //     Route::delete('/{id}', [AdminController::class, 'delete']);

    // });

    // Route::group(['prefix' => 'category'], function () {

    //     Route::get('/', [CategoryController::class, 'index']);
    //     Route::post('/', [CategoryController::class, 'store']);
    //     Route::get('/{id}', [CategoryController::class, 'show']);
    //     Route::put('/{id}', [CategoryController::class, 'update']);
    //     Route::delete('/{id}', [CategoryController::class, 'delete']);

    // });

    // Route::group(['prefix' => 'item'], function () {

    //     Route::get('/', [ItemController::class, 'index']);
    //     Route::post('/', [ItemController::class, 'store']);
    //     Route::get('/{id}', [ItemController::class, 'show']);
    //     Route::put('/{id}', [ItemController::class, 'update']);
    //     Route::delete('/{id}', [ItemController::class, 'delete']);

    // });

    // Route::group(['prefix' => 'promotion'], function () {

    //     Route::get('/', [PromotionController::class, 'index']);
    //     Route::post('/', [PromotionController::class, 'store']);
    //     Route::get('/{id}', [PromotionController::class, 'show']);
    //     Route::put('/{id}', [PromotionController::class, 'update']);
    //     Route::delete('/{id}', [PromotionController::class, 'delete']);

    // });

    // Route::group(['prefix' => 'delivery-address'], function () {

    //     Route::get('/', [DeliveryAddressController::class, 'index']);
    //     Route::post('/', [DeliveryAddressController::class, 'store']);
    //     Route::get('/{id}', [DeliveryAddressController::class, 'show']);
    //     Route::put('/{id}', [DeliveryAddressController::class, 'update']);
    //     Route::delete('/{id}', [DeliveryAddressController::class, 'delete']);

    // });

    // Route::group(['prefix' => 'order'], function () {

    //     Route::get('/', [OrderController::class, 'index']);
    //     Route::post('/', [OrderController::class, 'store']);
    //     Route::get('/{id}', [OrderController::class, 'show']);
    //     Route::put('/{id}', [OrderController::class, 'update']);
    //     Route::delete('/{id}', [OrderController::class, 'delete']);

    // });

});
