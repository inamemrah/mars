<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('plateau/get', 'App\Http\Controllers\Plateau@get')->name('get.plateau');
Route::get('plateau/create', 'App\Http\Controllers\Plateau@create')->name('create.plateau');

Route::get('rover/get', 'App\Http\Controllers\Rover@get')->name('get.rover');
Route::get('rover/create', 'App\Http\Controllers\Rover@create')->name('create.rover');
Route::get('rover/getState', 'App\Http\Controllers\Rover@getState')->name('getState.rover');
Route::get('rover/sendCommand', 'App\Http\Controllers\Rover@sendCommand')->name('sendCommand.rover');