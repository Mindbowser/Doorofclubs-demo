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

Route::get('/', function () { return view('welcome'); });
Route::get('/login', function() { return View::make('login'); });

Route::auth();

Route::get('/home', 'HomeController@index');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::get('/post', 'PostController@index');
Route::get('/post/create','PostController@create');  
Route::get('/post/show/{id}','PostController@show');
Route::post('/post/store','PostController@store');
Route::get('/post/posts','PostController@posts');
Route::get('/post/uppostcount/{id}','PostController@upPostCount');
Route::get('/post/downpostcount/{id}','PostController@downPostCount');
//Route::resource('/post', 'PostController');

Route::controllers([
   'password' => 'Auth\PasswordController',
]);
