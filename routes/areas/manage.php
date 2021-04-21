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

Route::group(['middleware' => 'role:developer|superadmin'], function(){
  /* --- Empresa --- */
  Route::resource('empresa', 'EmpresaController');

  /* --- Users --- */
  Route::get('user/create/{empresa}', 'UserController@create')->name('user.create');
  Route::post('user/create/{empresa}', 'UserController@store')->name('user.store');
  Route::patch('user/{user}/password/reset', 'UserController@password')->name('user.password');
  Route::resource('user', 'UserController')
        ->except(['index', 'create', 'store']);

  /* --- Covid19 --- */
  Route::resource('covid19', 'Covid19PreguntaController')
    ->parameters(['covid19' => 'pregunta']);

  /* --- Ayuda --- */
  Route::resource('ayuda', 'AyudaController');

  /* --- Plantilla --- */
  Route::get('plantilla/create', 'PlantillaController@create')->name('plantilla.create');
  Route::post('plantilla', 'PlantillaController@store')->name('plantilla.store');

  /* --- Inventario V2 - Unidad ---*/
  Route::patch('unidad/{unidad}/status', 'UnidadController@status')->name('unidad.status');
  Route::resource('unidad', 'UnidadController');
});
