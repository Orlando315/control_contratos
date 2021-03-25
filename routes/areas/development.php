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

Route::group(['middleware' => 'role:developer'], function(){
  /* --- Modulos --- */
  Route::resource('modulo', 'ModuloController');

  /* --- Roles --- */
  Route::resource('role', 'RoleController');

  /* --- Permissions --- */
  Route::resource('permission', 'PermissionController');

  /* --- Plantillas - Variables --- */
  Route::post('variable/generate/statics', 'PlantillaVariableController@generate')->name('variable.generate');
  Route::resource('variable', 'PlantillaVariableController');

  /* --- Fixs --- */
  Route::get('fix', 'FixController@index')->name('fix.index');
  Route::get('fix/{fix}', 'FixController@route')->name('fix.route');
});
