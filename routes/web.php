<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/view', function() {
//     return view('view');
// });

// Route::get('pay', 'PayOrderController@store');
Auth::routes();

// Route::get('/', 'HomeController@index')->name('home');
Route::get('/', 'PostController@index');
Route::resource('post', 'PostController');

Route::get('/login/twitch', 'Auth\TwitchController@redirectToProvider')->name('twitch-login');
Route::get('/login/twitch/cb', 'Auth\TwitchController@handleProviderCallback');

Route::post('/ajax/like', 'AjaxController@like')->name('ajax.like');
Route::post('/ajax/comment', 'AjaxController@comment')->name('ajax.comment');
Route::get('/ajax/comment', 'AjaxController@getComments')->name('ajax.comment.get');