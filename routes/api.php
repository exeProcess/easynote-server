<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EasyNoteController;

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

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix'=> 'auth'
], function($router){
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('profile', 'AuthController@profile');
    Route::post('refresh', 'AuthController@refresh');
    //Route::post('', 'AuthController@login');
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers'
], function($router){
    //Route::resource('notes', EasyNoteController::class);
    Route::post('note/post', [EasyNoteController::class, 'store']);
    Route::get('notes/index', [EasyNoteController::class, 'index']);
    Route::put('note/update', [EasyNoteController::class, 'update']);

});
