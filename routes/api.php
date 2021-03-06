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

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function() {
   Route::get('/user', function (Request $request) {
       return $request->user();
   });

   Route::group(['prefix' => 'media'], function() {
       Route::get('/', 'MediaController@index');
       Route::post('/', 'MediaController@create');
       Route::delete('/{media}', 'MediaController@delete');
   });
});

Route::group(['prefix' => 'media', 'namespace' => 'Api'], function() {
    Route::get('/', 'MediaController@index');
    Route::get('/{media}', 'MediaController@show');
});
