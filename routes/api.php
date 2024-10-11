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
//Route::get('/login','FrontendController@login')->name('login.form');
Route::post('/register','FrontendController@registerSubmit');
Route::post('/login','FrontendController@loginSubmit');
Route::get('/logout','FrontendController@logout');
Route::get('/users','UsersController@index');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
