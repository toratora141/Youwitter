<?php

use App\Models\YoutubeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MovieListCreate;
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

// Auth::routes();

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/user/logout', 'App\Http\Controllers\Auth\LoginController@logout');
    Route::post('/user/update', 'App\Http\Controllers\UserController@update');
    Route::get('/user/fetch', 'App\Http\Controllers\UserController@fetchProf');
    Route::get('/user/fetchUser', 'App\Http\Controllers\UserController@fetchUser');
    Route::get('/user/fetchProf', 'App\Http\Controllers\UserController@fetchProf');
    Route::post('/user/videoList/create', 'App\Http\Controllers\VideoListController@listCreate');

    Route::get('/user/follow/fetch', 'App\Http\Controllers\FollowController@fetch');
    Route::get('/user/follow/list', 'App\Http\Controllers\FollowController@list');
    Route::post('/user/follow', 'App\Http\Controllers\FollowController@follow');
    Route::post('/user/follow/delete', 'App\Http\Controllers\FollowController@delete');

    Route::get('/videoList/fetch', 'App\Http\Controllers\VideoListController@fetch');
});
Route::get('/searchUser', 'App\Http\Controllers\UserController@searchUser');
Route::post('/users/register', 'App\Http\Controllers\UserController@register');
Route::post('/user/login', 'App\Http\Controllers\Auth\LoginController@originalLogin');
