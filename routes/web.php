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
Route::get('ad/{ad} ','AdController@show')->middleware('check');
//Route::resource('photo','UserImageController');
Route::get('/user/{id}', 'ProfileController@show')->name('user');
Route::get('/user/{id}/clear', 'ProfileController@nukeAds')->name('user.clear');
Route::get('/user/{id}/destroy', 'ProfileController@nukeUser')->name('user.destroy');
Route::get('/user/{id}/unbindVK', 'ProfileController@unbindVK')->name('user.unbindVK');

Route::get('register/vk', 'Auth\RegisterController@passVKData')->name('register.vk');
Route::get('login/vk', 'SocialProviderController@redirectToProvider')->name('vk');
Route::get('login/vk/callback', 'SocialProviderController@handleProviderCallback');

Route::get('/verify/{key}','ProfileController@verify')->name('verify');
Route::put('/user/{key}','ProfileController@update')->name('user.update');
Route::any('/teapot',function() {abort(418);});