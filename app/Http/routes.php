<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function() {
	return redirect('/contact');
});
Route::get('/contact', 'DefaultController@tab1');
Route::get('/contact/{id}', 'DefaultController@viewContact');
Route::post('/contact/create', 'DefaultController@createContact');
Route::post('/send/{id}', 'DefaultController@sendMessage');
Route::get('/history', 'DefaultController@history');
