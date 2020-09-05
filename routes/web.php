<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'login'] ,function() {
    Route::get('/', 'AuthController@login')->name('login');
    Route::get('/callback', 'AuthController@loginCallback');
});

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::view('/{path?}', 'app')
    ->where('path', '.*')
    ->name('react');
