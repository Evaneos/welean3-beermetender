<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

// Groupe de routes pour le versioning d'API
Route::group(array('prefix' => 'api/v1', 'before' => 'auth.rest.token'), function()
{
    Route::resource('users', 'UserController', array('except' => 'store'));
    Route::resource('beers', 'BeerController');
});

Route::post('/api/v1/users', 'UserController@store');

Route::post('/api/v1/authenticate', 'FacebookLoginController@login');
