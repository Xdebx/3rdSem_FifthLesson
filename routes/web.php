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

Route::get('/artists', [
  'uses' => 'ArtistController@getArtists',
   'as' => 'getArtists'
]);

Route::get('/listeners', [
    'uses' => 'ListenerController@getListeners',
     'as' => 'getListeners'
  ]);

Route::get('/albums', [
    'uses' => 'AlbumController@getAlbums',
     'as' => 'getAlbums'
  ]);

Route::get('/search/{search?}',['uses' => 'SearchController@search','as' => 'search'] );
Route::resource('artist', 'ArtistController')->except(['index', 'show']);
Route::resource('album', 'AlbumController')->except(['index', 'show']);
Route::resource('listener', 'ListenerController')->except(['index','show']);

Route::post('/artist/import', 'ArtistController@import')->name('artistImport');
Route::post('/listener/import', 'ListenerController@import')->name('listenerImport');




 
