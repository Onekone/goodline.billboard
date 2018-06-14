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
Route::resource('photo','UserImageController');
Route::get('/user/{id}', 'ProfileController@show')->name('user');
Route::get('/verify/{key}','ProfileController@verify')->name('verify');
Route::put('/user/{key}','ProfileController@update')->name('user.update');
Route::any('/teapot',function() {return view('errors.418');} );
