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
Route::get('registro', 'UsuariosController@create')->name('empresas.create');
Route::post('registro', 'UsuariosController@store')->name('empresas.store');

/* --- Cronjob --- */
Route::get('cronjob/asistencias/create', 'HomeController@cronjobAsistencias');

/* --- Solo usuarios autenticados --- */
Route::group(['middleware' => 'auth'], function () {

  /* --- Dashboard --- */
  Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

  /* --- Perfil --- */
  Route::get('/perfil', 'UsuariosController@perfil')->name('perfil');
  Route::get('/perfil/edit', 'UsuariosController@edit' )->name('perfil.edit');
  Route::patch('/perfil', 'UsuariosController@update')->name('perfil.update');
  Route::patch('/perfil/password', 'UsuariosController@password')->name('perfil.password');

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
  Route::prefix('/admin')->name('admin.')->namespace('Admin')->middleware('role:staff')->group(function(){
    /* --- Solo usuarios 1 Empresa (Super admin) --- */
    Route::group(['middleware' => 'role:1'], function(){
      /* --- Contratos --- */
      Route::resource('contratos', 'ContratosController')->except([
        'index',
        'show'
      ]);

      /* --- Faenas --- */
      Route::resource('faena', 'FaenaController')->except([
        'index',
      ]);

      /* --- Contratos - Requisitos --- */
      Route::resource('requisito', 'RequisitoController')
          ->except(['index', 'create', 'store']);
      Route::get('requisito/{contrato}/{type}', 'RequisitoController@create')->name('requisito.create');
      Route::post('requisito/{contrato}/{type}', 'RequisitoController@store')->name('requisito.store');

      /* --- Contratos / Documentos por expirar --- */
      Route::get('expiration/{type}/{days}', 'HomeController@aboutToExpire')->name('expiration');

      /* --- Migrar relaciones de Documentos (Contrato / Empleado) a morph --- */
      Route::get('documentos/update/morph', 'DocumentosController@migrateToMorph');
      /* --- Migrar informacion de TransporteConsumo a Documentos --- */
      Route::get('documentos/migrate/adjuntos/morph', 'DocumentosController@migrateTransporteAdjuntosToDocumentos');
    });

    /* --- Solo usuarios 1 Empresa (Super admin) y 2 Administrador --- */
    Route::group(['middleware' => 'role:2'], function(){
      /* --- Usuarios --- */
      Route::post('usuarios/{usuario}/get', 'UsuariosController@get')->name('usuarios.get');
      Route::resource('usuarios', 'UsuariosController');
      Route::patch('usuarios/password/{usuario}', 'UsuariosController@password')->name('usuarios.password');

      /* --- Plantillas --- */
      Route::get('plantilla/{plantilla}/variables', 'PlantillaController@variables')->name('plantilla.variables');
      Route::resource('plantilla', 'PlantillaController');

      /* --- Variables --- */
      Route::post('variable/generate/statics', 'PlantillaVariableController@generateStatic')->name('variable.generate');
      Route::resource('variable', 'PlantillaVariableController')
            ->except(['index', 'show']);

      /* --- Documento plantillas --- */
      Route::get('documento/plantilla/create/{contrato?}/{empleado?}', 'PlantillaDocumentoController@create')->name('plantilla.documento.create');
      Route::resource('documento/plantilla', 'PlantillaDocumentoController', ['names' => 'plantilla.documento'])
            ->parameters([
              'plantilla' => 'documento',
            ])
            ->except(['create']);

      /* --- Empleados --- */
      Route::get('empleados/{empleado}/print', 'EmpleadosController@print')->name('empleados.print');
      Route::patch('empleados/{empleado}/toggle', 'EmpleadosController@toggleTipo')->name('empleados.toggleTipo');
      Route::post('empleados/contratos/{contrato}', 'EmpleadosController@getByContrato');
      Route::post('empleados/{empleado}/export', 'EmpleadosController@export')->name('empleados.export');
      Route::get('empleados/{contrato}/import', 'EmpleadosController@importCreate')->name('empleados.import.create');
      Route::post('empleados/{contrato}/import', 'EmpleadosController@importStore')->name('empleados.import.store');
      Route::get('empleados/{contrato}/create', 'EmpleadosController@create')->name('empleados.create');
      Route::post('empleados/{contrato}/create', 'EmpleadosController@store')->name('empleados.store');
      Route::resource('empleados', 'EmpleadosController')->except([
        'create',
        'store'
      ]);

      /* --- Empleados - Contratos --- */
      Route::patch('empleados/{empleado}/contrato/cambio', 'EmpleadosContratoController@cambio')->name('empleados.contrato.cambio');
      Route::get('empleados/{empleado}/contrato/create', 'EmpleadosContratoController@create')->name('empleados.contrato.create');
      Route::post('empleados/{empleado}/contrato', 'EmpleadosContratoController@store')->name('empleados.contrato.store');
      Route::get('empleados/{empleado}/contrato/edit', 'EmpleadosContratoController@edit')->name('empleados.contrato.edit');
      Route::patch('empleados/{empleado}/contrato/edit', 'EmpleadosContratoController@update')->name('empleados.contrato.update');

      /* --- Solicitudes --- */
      Route::resource('solicitud', 'SolicitudController')
            ->except(['create', 'store']);

      /* --- Empleados - Eventos --- */
      Route::get('empleados/eventos/', 'EmpleadosEventosController@index')->name('eventos.index');
      Route::post('empleados/eventos/{empleado}', 'EmpleadosEventosController@store')->name('eventos.store');
      Route::delete('empleados/eventos/{evento}', 'EmpleadosEventosController@destroy')->name('eventos.destroy');
      Route::put('empleados/eventos/{evento}/status', 'EmpleadosEventosController@status')->name('eventos.status');

      /* --- Sueldos --- */
      Route::get('sueldos/{contrato}', 'EmpleadosSueldosController@index')->name('sueldos.index');
      Route::get('sueldos/{sueldo}/show', 'EmpleadosSueldosController@show')->name('sueldos.show');
      Route::get('sueldos/{contrato}/create', 'EmpleadosSueldosController@create')->name('sueldos.create');
      Route::post('sueldos/{contrato}', 'EmpleadosSueldosController@store')->name('sueldos.store');

      /* --- Anticipos --- */
      Route::resource('anticipos', 'AnticiposController')->except([
        'create'
      ]);
      Route::get('anticipos/serie/{serie}', 'AnticiposController@serie')->name('anticipos.show.serie');
      Route::get('anticipos/serie/{serie}/print', 'AnticiposController@printSerie')->name('anticipos.print.serie');
      Route::get('anticipos/{anticipo}/download', 'AnticiposController@download')->name('anticipos.download');
      Route::get('anticipos/create/individual', 'AnticiposController@create')->name('anticipos.individual');
      Route::get('anticipos/create/masivo', 'AnticiposController@masivo')->name('anticipos.masivo');
      Route::post('anticipos/create/masivo', 'AnticiposController@storeMasivo')->name('anticipos.storeMasivo');
      Route::post('anticipos/empleados/{contrato}', 'AnticiposController@getEmpleados');
      Route::put('anticipos/{anticipo}/status', 'AnticiposController@status')->name('anticipos.status');
      Route::delete('anticipos/serie/{serie}', 'AnticiposController@destroySerie')->name('anticipos.destroy.serie');

      /* --- Gastos --- */
      Route::resource('gastos', 'GastosController');

      /* --- Facturas --- */
      Route::resource('facturas', 'FacturasController');
      Route::get('facturas/{factura}/download/{adjunto}', 'FacturasController@download')->name('facturas.download');

      /* --- Transportes --- */
      Route::resource('transportes', 'TransportesController')->except([
        'index',
        'show'
      ]);
      Route::post('transportes/{transporte}/add/', 'TransportesController@storeContratos')->name('transportes.contratos.store');
      Route::delete('transportes/contratos/{contrato}', 'TransportesController@destroyContratos')->name('transportes.contratos.destroy');

      /* --- Inventarios --- */
      Route::patch('inventarios/clone/{inventario}', 'InventariosController@clone')->name('inventarios.clone');

      /* --- Clientes --- */
      Route::get('cliente/create/{type}', 'ClienteController@create')->name('cliente.create');
      Route::post('cliente/store/{type}', 'ClienteController@store')->name('cliente.store');
      Route::post('cliente/busqueda/sii', 'ClienteController@busquedaSii')->name('cliente.busqueda.sii');
      Route::resource('cliente', 'ClienteController')
            ->except(['create', 'store']);

      /* --- Direcciones --- */
      Route::get('direccion/create/{id}/{type}', 'DireccionController@create')->name('direccion.create');
      Route::post('direccion/create/{id}/{type}', 'DireccionController@store')->name('direccion.store');
      Route::patch('direccion/{direccion}/status', 'DireccionController@status')->name('direccion.status');
      Route::resource('cliente/direccion', 'DireccionController')
            ->only(['edit', 'update', 'destroy']);

      /* --- Contactos --- */
      Route::get('contacto/create/{id}/{type}', 'ContactoController@create')->name('contacto.create');
      Route::post('contacto/create/{id}/{type}', 'ContactoController@store')->name('contacto.store');
      Route::resource('contacto', 'ContactoController')
            ->only(['edit', 'update', 'destroy']);

      /* --- Proveedores --- */
      Route::get('proveedor/create/{type}', 'ProveedorController@create')->name('proveedor.create');
      Route::post('proveedor/store/{type}', 'ProveedorController@store')->name('proveedor.store');
      Route::post('proveedor/busqueda/sii', 'ProveedorController@busquedaSii')->name('proveedor.busqueda.sii');
      Route::resource('proveedor', 'ProveedorController')
            ->except(['create', 'store']);

      /* --- Cotizaciones --- */
      Route::get('cotizacion/create/{cliente?}', 'CotizacionController@create')->name('cotizacion.create');
      Route::get('cotizacion/{cotizacion}/productos', 'CotizacionController@productos')->name('cotizacion.productos');
      Route::resource('cotizacion', 'CotizacionController')
            ->except('create');

      /* --- Cotizaciones - Productos --- */
      Route::resource('cotizacion/producto', 'CotizacionProductoController')
            ->names('cotizacion.producto')
            ->only(['destroy']);

      /* --- Facturaciones --- */
      Route::get('facturacion/create/{cotizacion?}', 'FacturacionController@create')->name('facturacion.create');
      Route::resource('facturacion', 'FacturacionController')
            ->only(['index', 'store', 'show']);

      /* --- Facturaciones --- */
      Route::get('pago/create/{facturacion}', 'PagoController@create')->name('pago.create');
      Route::post('pago/create/{facturacion}', 'PagoController@store')->name('pago.store');
      Route::get('pago/{pago}/download', 'PagoController@download')->name('pago.download');
      Route::resource('pago', 'PagoController')
            ->only(['edit', 'update', 'destroy']);

      /* --- Reportes --- */
      Route::get('reportes/inventarios', 'ReportesController@inventariosIndex')->name('reportes.inventarios.index');
      Route::post('reportes/inventarios', 'ReportesController@inventariosGet')->name('reportes.inventarios.get');
      Route::get('reportes/facturas', 'ReportesController@facturasIndex')->name('reportes.facturas.index');
      Route::post('reportes/facturas', 'ReportesController@facturasGet')->name('reportes.facturas.get');
      Route::get('reportes/eventos', 'ReportesController@eventosIndex')->name('reportes.eventos.index');
      Route::post('reportes/eventos', 'ReportesController@eventosGet')->name('reportes.eventos.get');
      Route::get('reportes/sueldos', 'ReportesController@sueldosIndex')->name('reportes.sueldos.index');
      Route::post('reportes/sueldos', 'ReportesController@sueldosGet')->name('reportes.sueldos.get');
      Route::get('reportes/anticipos', 'ReportesController@anticiposIndex')->name('reportes.anticipos.index');
      Route::post('reportes/anticipos', 'ReportesController@anticiposGet')->name('reportes.anticipos.get');
      Route::get('reportes/transportes', 'ReportesController@transportesIndex')->name('reportes.transportes.index');
      Route::post('reportes/transportes', 'ReportesController@transportesGet')->name('reportes.transportes.get');
      Route::get('reportes/comidas', 'ReportesController@comidasIndex')->name('reportes.comidas.index');
      Route::post('reportes/comidas', 'ReportesController@comidasGet')->name('reportes.comidas.get');
      Route::get('reportes/reemplazos', 'ReportesController@reemplazosIndex')->name('reportes.reemplazos.index');
      Route::post('reportes/reemplazos', 'ReportesController@reemplazosGet')->name('reportes.reemplazos.get');
      Route::get('reportes/general', 'ReportesController@generalIndex')->name('reportes.general.index');
      Route::post('reportes/general', 'ReportesController@generalGet')->name('reportes.general.get');
    });

    /* --- Solo usuarios 1 Empresa (Super admin), 2 Administrador y 3 Supervisor --- */
    Route::group(['middleware' => 'role:3'], function(){
      /* --- Contratos --- */
      Route::resource('contratos', 'ContratosController')->only([
        'index',
        'show'
      ]);
      Route::get('contratos/calendar/{contrato}', 'ContratosController@calendar')->name('contratos.calendar');
      Route::post('contratos/export/{contrato}', 'ContratosController@exportJornadas')->name('contratos.exportJornadas');

      /* --- Carpetas --- */
      Route::get('carpeta/create/{type}/{id}/{carpeta?}', 'CarpetaController@create')->name('carpeta.create');
      Route::post('carpeta/create/{type}/{id}/{carpeta?}', 'CarpetaController@store')->name('carpeta.store');
      Route::resource('carpeta', 'CarpetaController')
            ->parameters(['carpeta' => 'carpeta'])
            ->except(['create', 'store']);

      /* Documentos - Descarga */
      Route::get('documentos/download/{documento}', 'DocumentosController@download')->name('documentos.download');

      /* --- Documentos --- */
      Route::resource('documentos', 'DocumentosController')->only([
        'edit',
        'update',
        'destroy'
      ]);
      Route::get('documentos/{type}/{id}/{carpeta?}', 'DocumentosController@create')->name('documentos.create');
      Route::post('documentos/{type}/{id}/{carpeta?}', 'DocumentosController@store')->name('documentos.store');

      /* --- Etiquetas --- */
      Route::resource('etiquetas', 'EtiquetasController');

        /* --- Transportes --- */
      Route::resource('transportes', 'TransportesController')->only([
        'index',
        'show'
      ]);

      /* --- Transporte consumo --- */
      Route::resource('transportes/consumos', 'TransportesConsumosController')->except([
        'create',
        'store'
      ]);
      Route::get('transportes/consumos/create/{transporte}', 'TransportesConsumosController@create')->name('consumos.create');
      Route::post('transportes/consumos/{transporte}', 'TransportesConsumosController@store')->name('consumos.store');

      /* --- Inventario ---*/
      Route::resource('inventarios', 'InventariosController');
      Route::get('inventarios/download/{inventario}', 'InventariosController@download')->name('inventarios.download');
      
      /* --- Inventarios Entregas ---*/
      Route::get('entregas/{inventario}', 'InventariosEntregasController@create')->name('entregas.create');
      Route::post('entregas/{inventario}', 'InventariosEntregasController@store')->name('entregas.store');
      Route::delete('entregas/{entrega}', 'InventariosEntregasController@destroy')->name('entregas.destroy');
    });
  });
});
