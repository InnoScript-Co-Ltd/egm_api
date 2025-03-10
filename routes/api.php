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

Route::middleware(['api'])->group(function () {

    Route::middleware('jwt')->group(function () {

        // Route::group(['prefix'=>'customer'], function(){
        //     Route::get('/','CustomerController@index');
        //     Route::post('/','CustomerController@store');
        //     Route::get('/{id}','CustomerController@show');
        //     Route::put('/{id}','CustomerController@update');
        //     Route::delete('/{id}','CustomerController@destroy');

        // });

});
});