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


Route::middleware(['cors', 'json.response'])->group(function () {
    Route::middleware('auth:api')->get('/currencies', 'App\Http\Controllers\AuthController@currencies');
    Route::middleware('auth:api')->get('/markets', 'App\Http\Controllers\AuthController@markets');


    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
});

