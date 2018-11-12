<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['middleware' => ['cors']],function(){
    Route::post('/user/register','UserController@register');

    Route::post('/user/login','UserController@login');

    Route::get('/prefix','UserController@getPrefix');

    Route::get('/users','UserController@showUsers')->middleware('jwt.auth')->name('all users');

    Route::get('/users/{id}','UserController@showUser')->name('get user');

    Route::post('/users','UserController@addUser')->name('insert user');

    Route::put('/users/{id}','UserController@updateUser')->name('update user');

    Route::delete('/users/{id}','UserController@delete')->name('delete user');
});
