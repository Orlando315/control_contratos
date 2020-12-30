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

/* --- Users --- */
Route::resource('user', 'UserController');
Route::patch('user/{user}/status', 'UserController@status')->name('user.status');
Route::patch('user/{user}/password/reset', 'UserController@password')->name('user.password');

/* --- Empresa --- */
Route::resource('empresa', 'EmpresaController');
