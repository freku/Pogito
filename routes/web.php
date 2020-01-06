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

Auth::routes();

// Route::get('/', 'HomeController@index')->name('home');
Route::get('/', 'PostController@index');
Route::get('/sort-new', 'PostController@sort_new');
Route::get('/mod/reports', 'ModController@reports');

Route::resource('post', 'PostController');

Route::get('/x/{name}', 'ProfileController@show');

Route::get('/login/twitch', 'Auth\TwitchController@redirectToProvider')->name('twitch-login');
Route::get('/login/twitch/cb', 'Auth\TwitchController@handleProviderCallback');

Route::post('/ajax/like', 'AjaxController@like')->name('ajax.like');
Route::post('/ajax/comment', 'AjaxController@comment')->name('ajax.comment');
Route::get('/ajax/comment', 'AjaxController@getComments')->name('ajax.comment.get');

Route::get('/ajax/post', 'AjaxController@getPosts')->name('ajax.post.get');
Route::get('/ajax/like', 'AjaxController@like')->name('ajax.like');
Route::get('/ajax/test', 'AjaxController@test')->name('ajax.test');
Route::get('/ajax/report', 'AjaxController@report')->name('ajax.report');
Route::get('/ajax/report/action', 'AjaxController@report_action')->name('ajax.report.action');