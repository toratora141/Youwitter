<?php

use App\Models\YoutubeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/user/fetch', 'App\Http\Controllers\UserController@fetchProf');
    Route::post('/user/update', 'App\Http\Controllers\UserController@update');
    Route::post('/user/videoList/create', 'App\Http\Controllers\VideoListController@listCreate');

    Route::get('/videoList/fetch', 'App\Http\Controllers\VideoListController@fetch');
});
Route::post('/users/register', 'App\Http\Controllers\UserController@register');
Route::post('/user/login', 'App\Http\Controllers\Auth\LoginController@login');
