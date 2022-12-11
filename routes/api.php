<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', '\App\Http\Controllers\AuthController@register');
Route::post('checklogin', '\App\Http\Controllers\AuthController@check');


// Route::group(['middleware' => 'auth:api'], function() {
//     Route::get('user', '\App\Http\Controllers\UserProfilController@index');
//     Route::get('user/{id}', '\App\Http\Controllers\UserProfilController@show');
//     Route::put('user/{id}','\App\Http\Controllers\UserProfilController@update');
//     Route::delete('user/{id}', '\App\Http\Controllers\UserProfilController@destroy');

//     Route::post('janjitemu','\App\Http\Controllers\JanjiTemuController@store');
//     Route::get('janjitemu', '\App\Http\Controllers\JanjiTemuController@index');
//     Route::get('janjitemu/{id}', '\App\Http\Controllers\JanjiTemuController@show');
//     Route::put('janjitemu/{id}','\App\Http\Controllers\JanjiTemuController@update');
//     Route::delete('janjitemu/{id}', '\App\Http\Controllers\JanjiTemuController@destroy');
    

//     Route::post('logout', '\App\Http\Controllers\AuthController@logout');
// });


Route::resource('user',\App\Http\Controllers\UserProfilController::class);
Route::resource('janji',\App\Http\Controllers\JanjiTemuController::class);
Route::resource('vaksin',\App\Http\Controllers\VaksinController::class);
Route::resource('mahasiswa',\App\Http\Controllers\MahasiswaController::class);