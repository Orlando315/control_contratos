<?php

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

/*--- RUTAS DE LOGIN ---*/
Route::get('/', function(){
  return view('login');
})->name('login.view');
Route::get('login', function(){
  return view('login');
});
Route::post('auth', 'LoginController@auth')->name('login.auth');
Route::match(['get', 'post'], '/logout', 'LoginController@logout')->name('login.logout');

/* --- Empresas --- */
Route::get('registro', 'EmpresasController@create')->name('empresas.create');
Route::post('registro', 'EmpresasController@store')->name('empresas.store');

/* --- Solo usuarios autenticados --- */
Route::group([ 'middleware' => ['auth'] ], function () {

  /* --- Dashboard --- */
  Route::get('dashboard', 'LoginController@dashboard')->name('dashboard');
  /* --- Empresas --- */
  Route::get('/empresa/edit', 'EmpresasController@edit')->name('empresas.edit');
  Route::patch('/perfil/empresas', 'EmpresasController@update')->name('empresas.update');

  /* --- Contratos --- */
  Route::resource('contratos', 'ContratosController');

  /* --- Transportes --- */
  Route::resource('transportes', 'TransportesController');
  /* --- Transporte consumo --- */
  Route::resource('transportes/consumos', 'TransportesConsumosController')->except([
    'create',
    'store'
  ]);
  Route::get('transportes/consumos/create/{transporte}', 'TransportesConsumosController@create')->name('consumos.create');
  Route::post('transportes/consumos/{transporte}', 'TransportesConsumosController@store')->name('consumos.store');
  Route::get('transportes/consumos/download/{consumo}', 'TransportesConsumosController@download')->name('consumos.download');

  /* --- Anticipos --- */
  Route::resource('anticipos', 'AnticiposController')->except([
    'create'
  ]);
  Route::get('anticipos/create/individual', 'AnticiposController@create')->name('anticipos.individual');
  Route::get('anticipos/create/masivo', 'AnticiposController@masivo')->name('anticipos.masivo');
  Route::post('anticipos/empleados/{contrato}', 'AnticiposController@getEmpleados');
  Route::post('anticipos/create/masivo', 'AnticiposController@storeMasivo')->name('anticipos.storeMasivo');

  /* --- Usuarios --- */
  Route::resource('usuarios', 'UsuariosController');
  /* --- Perfil --- */
  Route::get('/perfil', 'UsuariosController@perfil')->name('usuarios.perfil');
  Route::get('/perfil/edit', 'UsuariosController@editPerfil' )->name('usuarios.editPerfil');
  Route::patch('/perfil', 'UsuariosController@updatePerfil')->name('usuarios.updatePerfil');
  Route::patch('/perfil/password', 'UsuariosController@password')->name('usuarios.password');

  /* --- Inventario ---*/
  Route::resource('inventarios', 'InventariosController');
  Route::get('inventarios/download/{inventario}', 'InventariosController@download')->name('inventarios.download');
  /* --- Entregas ---*/
  Route::get('inventarios/entregas/{inventario}', 'InventariosEntregasController@create')->name('entregas.create');
  Route::post('inventarios/entregas/{inventario}', 'InventariosEntregasController@store')->name('entregas.store');
  Route::delete('inventarios/entregas/{inventario}/{entrega}', 'InventariosEntregasController@destroy')->name('entregas.destroy');
  Route::resource('inventarios/entregas', 'InventariosEntregasController')->only([
    'index',
    'update'
  ]);

  /* --- Empleados --- */
  Route::post('empleados/contratos/{contrato}', 'EmpleadosController@getByContrato');
  Route::get('empleados/{empleado}/cambio', 'EmpleadosController@cambio')->name('empleados.cambio');
  Route::post('empleados/{empleado}/cambio', 'EmpleadosController@cambioStore')->name('empleados.cambioStore');
  Route::post('empleados/{empleado}/export', 'EmpleadosController@export')->name('empleados.export');
  Route::get('empleados/calendar', 'EmpleadosController@calendar')->name('empleados.calendar');
  Route::post('empleados/export', 'EmpleadosController@exportAll')->name('empleados.exportAll');
  Route::get('empleados/{contrato}/create', 'EmpleadosController@create')->name('empleados.create');
  Route::post('empleados/{contrato}/create', 'EmpleadosController@store')->name('empleados.store');
  Route::resource('empleados', 'EmpleadosController')->except([
    'create',
    'store'
  ]);

  /* --- Documentos --- */
  Route::resource('documentos', 'DocumentosController')->except([
    'show',
    'create',
    'store'
  ]);
  Route::get('documentos/empleados/{empleado}', 'DocumentosController@createEmpleado')->name('documentos.createEmpleado');
  Route::post('documentos/empleados/{empleado}', 'DocumentosController@storeEmpleado')->name('documentos.storeEmpleado');
  Route::get('documentos/contratos/{contrato}', 'DocumentosController@createContrato')->name('documentos.createContrato');
  Route::post('documentos/contratos/{contrato}', 'DocumentosController@storeContrato')->name('documentos.storeContrato');
  Route::get('documentos/download/{documento}', 'DocumentosController@download')->name('documentos.download');

  /* --- Eventos --- */
  Route::get('eventos/export', 'EmpleadosEventosController@events')->name('eventos.events');
  Route::post('eventos/export', 'EmpleadosEventosController@exportEventsTotal')->name('eventos.export');
  Route::post('eventos/events', 'EmpleadosEventosController@getEvents')->name('eventos.getEvents');
  Route::get('eventos/', 'EmpleadosEventosController@index')->name('eventos.index');
  Route::post('eventos/{empleado}', 'EmpleadosEventosController@store')->name('eventos.store');
  Route::delete('eventos/{evento}', 'EmpleadosEventosController@destroy')->name('eventos.destroy');
});