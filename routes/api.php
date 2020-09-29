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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'App\Http\Controllers\ApiAuthController@login');
Route::post('register', [\App\Http\Controllers\ApiAuthController::class, 'register']);

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'App\Http\Controllers\ApiAuthController@logout');

    Route::get('posts', 'App\Http\Controllers\PostController@index');
    Route::get('posts/{id}', 'App\Http\Controllers\PostController@show');
    Route::post('posts', 'App\Http\Controllers\PostController@store');
    Route::put('posts/{id}', 'App\Http\Controllers\PostController@update');
    Route::delete('posts/{id}', 'App\Http\Controllers\PostController@destroy');
});
