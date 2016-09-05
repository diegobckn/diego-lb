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

Route::get('/', function () {
    return view('welcome');
});



Route::get('/posts', 'PostController@getAll');
Route::get('/posts/{category_id?}/{q?}', 'PostController@getAll');
Route::post('/posts', 'PostController@add');
Route::post('/update', 'PostController@update');

