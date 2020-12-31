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

/*--- RUTAS DE LOGIN ---*/
Route::get('/', function(){
  return view('auth.login');
})->name('login.view');
Route::get('login', function(){
  return view('auth.login');
});

/* --- Auth ---*/
Route::post('auth', 'LoginController@auth')->name('login.auth');
Route::match(['get', 'post'], '/logout', 'LoginController@logout')->name('login.logout');

/* --- Recuperar contraseña --- */
Route::get('password', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showresetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

/* --- Confirmar contraseña --- */
Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

/* --- Empresas --- */
Route::get('registro', 'EmpresaController@create')->name('register');
Route::post('registro', 'EmpresaController@store');

/* --- Cronjob --- */
Route::get('cronjob/asistencias/create', 'HomeController@cronjobAsistencias');

/* --- Solo usuarios autenticados --- */
Route::group(['middleware' => 'auth'], function () {

  /* --- Dashboard --- */
  Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

  /* --- Perfil --- */
  Route::get('perfil', 'PerfilController@perfil')->name('perfil');
  Route::get('perfil/edit', 'PerfilController@edit' )->name('perfil.edit');
  Route::patch('perfil', 'PerfilController@update')->name('perfil.update');
  Route::patch('perfil/password', 'PerfilController@password')->name('perfil.password');

  /* --- Sueldos --- */
  Route::get('sueldos/{sueldo}/show', 'EmpleadosSueldosController@show')->name('sueldos.show');
  Route::patch('sueldos/{sueldo}/confirmar', 'EmpleadosSueldosController@recibido')->name('sueldos.confirmar');
  Route::get('sueldos/{sueldo}/download', 'EmpleadosSueldosController@download')->name('sueldos.download');

  /* --- Anticipos --- */
  Route::get('anticipos/{anticipo}/download', 'AnticiposController@download')->name('anticipos.download');
  Route::resource('anticipos', 'AnticiposController')
        ->only(['create', 'store']);

  /* --- Entregas ---*/
  Route::patch('entregas/{entrega}', 'InventariosEntregasController@update')->name('entregas.update');
  Route::get('entregas/{entrega}/download', 'InventariosEntregasController@download')->name('entregas.download');

  /* --- Solicitudes --- */
  Route::resource('solicitud', 'SolicitudController');
  Route::get('solicitud/{solicitud}/download', 'SolicitudController@download')->name('solicitud.download');

  /* --- Empleado - Eventos --- */
  Route::get('eventos/', 'EmpleadosEventosController@index')->name('eventos.index');
  Route::post('eventos/', 'EmpleadosEventosController@store')->name('eventos.store');

  /* --- Area Admin --- */
  Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function(){
    /* --- Usuarios Empresa y administradores --- */
    Route::group([], __DIR__ . '/areas/empresa.php');

    /* --- Solo superadmin --- */
    Route::middleware('ability:superadmin,super,require_all')
          ->prefix('manage')
          ->name('manage.')
          ->namespace('Manage')
          ->group(__DIR__ . '/areas/manage.php');

    /* --- Solo developers --- */
    Route::middleware('ability:developer,god,require_all')
          ->prefix('development')
          ->name('development.')
          ->namespace('Development')
          ->group(__DIR__ . '/areas/development.php');
  });
});
