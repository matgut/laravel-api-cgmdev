<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register','App\Http\Controllers\AuthController@register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('testOauth', 'App\Http\Controllers\AuthController@testOauth');
    
});

Route::get('users', 'App\Http\Controllers\UserDataController@getUsers');
Route::get('user/{id}', 'App\Http\Controllers\UserDataController@getUser');
Route::post('user', 'App\Http\Controllers\UserDataController@addUser');
Route::put('user/{id}', 'App\Http\Controllers\UserDataController@updateUser');
Route::delete('user/{id}', 'App\Http\Controllers\UserDataController@DeleteUser');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
