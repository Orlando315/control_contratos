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

/* --- Modulos --- */
Route::resource('modulo', 'ModuloController');

/* --- Roles --- */
Route::resource('role', 'RoleController');

/* --- Permissions --- */
Route::resource('permission', 'PermissionController');

/* --- Fixs --- */
Route::get('fix', 'FixController@index')->name('fix.index');
Route::get('fix/{fix}', 'FixController@route')->name('fix.route');
