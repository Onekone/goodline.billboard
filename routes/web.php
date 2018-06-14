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

Route::get('/','AdController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('ad','AdController');
//Route::resource('photo','UserImageController');
Route::get('/user/{id}', 'ProfileController@show')->name('user');
Route::get('/user/{id}/clear', 'ProfileController@nukeAds')->name('user.clear');
Route::get('/user/{id}/destroy', 'ProfileController@nukeUser')->name('user.destroy');
Route::get('/user/{id}/unbindVK', 'ProfileController@unbindVK')->name('user.unbindVK');

Route::get('register/vk', 'Auth\LoginController@redirectToProvider')->name('vk');
Route::get('login/vk', 'Auth\LoginController@redirectToProvider')->name('vk');
Route::get('login/vk/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/verify/{key}','ProfileController@verify')->name('verify');
Route::put('/user/{key}','ProfileController@update')->name('user.update');
Route::any('/teapot',function() {return view('errors.418');} );
