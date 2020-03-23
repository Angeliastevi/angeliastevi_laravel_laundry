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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', 'PetugasController@register');
Route::post('login', 'PetugasController@login');

Route::post('tambahplg','PelangganController@store')->middleware('jwt.verify');
Route::put('ubahplg/{id}','PelangganController@update')->middleware('jwt.verify');
Route::delete('hapusplg/{id}','PelangganController@destroy')->middleware('jwt.verify');

Route::post('tambahjc','JenisCuciController@store')->middleware('jwt.verify');
Route::put('ubahjc/{id}','JenisCuciController@update')->middleware('jwt.verify');
Route::delete('hapusjc/{id}','JenisCuciController@destroy')->middleware('jwt.verify');

Route::post('tambahtrs','TransaksiController@store')->middleware('jwt.verify');
Route::put('ubahtrs/{id}','TransaksiController@update')->middleware('jwt.verify');
Route::delete('hapustrs/{id}','TransaksiController@destroy')->middleware('jwt.verify');
Route::get('tampiltrs','TransaksiController@tampil')->middleware('jwt.verify');

Route::post('tambahdt','DetailTransController@store')->middleware('jwt.verify');
Route::put('ubahdt/{id}','DetailTransController@update')->middleware('jwt.verify');
Route::delete('hapusdt/{id}','DetailTransController@destroy')->middleware('jwt.verify');
Route::post('tampildt','DetailTransController@tampil')->middleware('jwt.verify');

