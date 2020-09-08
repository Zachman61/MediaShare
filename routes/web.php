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

Route::get('/m/{media}', function(\App\Media $media) {
    $pathToFile = Storage::path('media/'. $media->filename);
    return response()->file($pathToFile);
});

Route::get('/t/{media}', function(\App\Media $media) {
   if ($media->type !== 'video') {
       return response('', 404);
   }

   $pathToFile = Storage::path('thumbs/'. $media->id. '.jpg');
   return response()->file($pathToFile);
});

Route::group(['prefix' => 'login'] ,function() {
    Route::get('/', 'AuthController@login')->name('login');
    Route::get('/callback', 'AuthController@loginCallback');
});

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::view('/{path?}', 'app')
    ->where('path', '.*')
    ->name('react');
