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

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::get('user', 'Auth\AuthController@user');
});

Route::get('/home_timeline', 'TimelineController@homeTimeline');
Route::get('/user_timeline', 'TimelineController@userTimeline');

Route::post('/post', 'PostController@storePost');
Route::post('/image', 'PostController@storeImage');
Route::post('/comment/parent', 'CommentController@storeParentComment');
Route::post('/comment/child', 'CommentController@storeChildComment');
Route::delete('/comment/{comment}', 'CommentController@destroy');
Route::get('/comment/parent', 'CommentController@index');
Route::get('/comment/child', 'CommentController@show');
Route::post('/good', 'GoodController@store');
Route::delete('/good', 'GoodController@destroy');
