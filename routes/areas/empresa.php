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

Route::group(['middleware' => 'role:developer|superadmin|empresa'], function(){
  /* --- Perfil Empresa --- */
  Route::get('empresa/perfil', 'EmpresaController@perfil')->name('empresa.perfil');
  Route::get('empresa/edit', 'EmpresaController@edit')->name('empresa.edit');
  Route::put('empresa/edit', 'EmpresaController@update')->name('empresa.update');

  /* --- Configuracion --- */
  Route::get('empresa/configuracion', 'ConfiguracionController@configuracion')->name('empresa.configuracion');
  Route::patch('empresa/configuracion/general', 'ConfiguracionController@general')->name('empresa.configuracion.general');
  Route::patch('empresa/configuracion/sii', 'ConfiguracionController@sii')->name('empresa.configuracion.sii');
  Route::patch('empresa/configuracion/terminos', 'ConfiguracionController@terminos')->name('empresa.configuracion.terminos');
  Route::patch('empresa/configuracion/covid19', 'ConfiguracionController@covid19')->name('empresa.configuracion.covid19');
  Route::patch('empresa/configuracion/requerimientos', 'ConfiguracionController@requerimientos')->name('empresa.configuracion.requerimientos');
});

/* --- Covid19 --- */
Route::get('covid19', 'Covid19Controller@index')->name('covid19.index');
Route::get('covid19/{respuesta}', 'Covid19Controller@show')->name('covid19.show');
Route::delete('covid19/{respuesta}', 'Covid19Controller@destroy')->name('covid19.destroy');

/* --- Contratos --- */
Route::resource('contrato', 'ContratosController')->except([
  'index',
  'show'
]);

/* --- Faenas --- */
Route::resource('faena', 'FaenaController')->except([
  'index',
]);

/* --- Centros de costos --- */
Route::resource('centro-costo', 'CentroCostoController')
->names('centro')
->parameters([
  'centro-costo' => 'centro',
])
->except([
  'index',
]);

/* --- Contratos - Requisitos --- */
Route::resource('requisito', 'RequisitoController')
    ->except(['index', 'create', 'store']);
Route::get('requisito/{contrato}/{type}', 'RequisitoController@create')->name('requisito.create');
Route::post('requisito/{contrato}/{type}', 'RequisitoController@store')->name('requisito.store');

/* --- Partida --- */
Route::get('partida/create/{contrato}', 'PartidaController@create')->name('partida.create');
Route::post('partida/create/{contrato}', 'PartidaController@store')->name('partida.store');
Route::get('partida/tipo/{contrato}/{tipo}', 'PartidaController@tipo')->name('partida.tipo');
Route::resource('partida', 'PartidaController')
->except([
  'index',
  'create',
  'store',
]);

/* --- Contratos / Documentos por expirar --- */
Route::get('expiration/{type}/{days}', 'HomeController@aboutToExpire')->name('expiration');

/* --- Migrar relaciones de Documentos (Contrato / Empleado) a morph --- */
Route::get('documento/update/morph', 'DocumentosController@migrateToMorph');
/* --- Migrar informacion de TransporteConsumo a Documentos --- */
Route::get('documento/migrate/adjuntos/morph', 'DocumentosController@migrateTransporteAdjuntosToDocumentos');

/* --- Usuarios --- */
Route::post('usuario/{usuario}/get', 'UsuariosController@get')->name('usuario.get');
Route::resource('usuario', 'UsuariosController');
Route::patch('usuario/password/{usuario}', 'UsuariosController@password')->name('usuario.password');

/* --- Plantillas --- */
Route::get('plantilla/{plantilla}/variables', 'PlantillaController@variables')->name('plantilla.variables');
Route::resource('plantilla', 'PlantillaController');

/* --- Variables --- */
Route::resource('variable', 'PlantillaVariableController')
      ->except(['index', 'show']);

/* --- Documento plantillas --- */
Route::get('documento/plantilla/create/{contrato?}/{empleado?}', 'PlantillaDocumentoController@create')->name('plantilla.documento.create');
Route::resource('documento/plantilla', 'PlantillaDocumentoController', ['names' => 'plantilla.documento'])
      ->parameters([
        'plantilla' => 'documento',
      ])
      ->except(['create']);

/* --- Postulante --- */
Route::resource('postulante', 'PostulanteController')
->except(['index']);

/* --- Empleados --- */
Route::get('empleado/{empleado}/print', 'EmpleadosController@print')->name('empleado.print');
Route::patch('empleado/{empleado}/role', 'EmpleadosController@changeRole')->name('empleado.changeRole');
Route::post('empleado/contratos/{contrato}', 'EmpleadosController@getByContrato');
Route::post('empleado/{empleado}/export', 'EmpleadosController@export')->name('empleado.export');
Route::get('empleado/import/{contrato?}', 'EmpleadosController@importCreate')->name('empleado.import.create');
Route::post('empleado/import', 'EmpleadosController@importStore')->name('empleado.import.store');
Route::resource('empleado', 'EmpleadosController');

/* --- Empleados - Contratos --- */
Route::patch('empleado/{empleado}/contrato/cambio', 'EmpleadosContratoController@cambio')->name('empleado.contrato.cambio');
Route::get('empleado/{empleado}/contrato/create', 'EmpleadosContratoController@create')->name('empleado.contrato.create');
Route::post('empleado/{empleado}/contrato', 'EmpleadosContratoController@store')->name('empleado.contrato.store');
Route::get('empleado/{empleado}/contrato/edit', 'EmpleadosContratoController@edit')->name('empleado.contrato.edit');
Route::patch('empleado/{empleado}/contrato/edit', 'EmpleadosContratoController@update')->name('empleado.contrato.update');

/* --- Solicitudes --- */
Route::resource('solicitud', 'SolicitudController')
      ->except(['create', 'store']);

/* --- Requerimiento Material --- */
Route::delete('requerimiento-material/producto/{producto}', 'RequerimientoMaterialController@destroyProducto')->name('requerimiento.material.producto.destroy');
Route::resource('requerimiento-material', 'RequerimientoMaterialController')
->names('requerimiento.material')
->parameters([
  'requerimiento-material' => 'requerimiento',
]);

/* --- Empleados - Eventos --- */
Route::get('empleado/evento/', 'EmpleadosEventosController@index')->name('evento.index');
Route::post('empleado/evento/{empleado}', 'EmpleadosEventosController@store')->name('evento.store');
Route::delete('empleado/evento/{evento}', 'EmpleadosEventosController@destroy')->name('evento.destroy');
Route::put('empleado/evento/{evento}/status', 'EmpleadosEventosController@status')->name('evento.status');

/* --- Previred - Proximamente --- */
Route::get('previred', function () {
  return view('admin.previred.index');
})
->name('previred.index');

/* --- Sueldos --- */
Route::get('sueldo/{contrato?}', 'EmpleadosSueldosController@index')->name('sueldo.index');
Route::get('sueldo/{sueldo}/show', 'EmpleadosSueldosController@show')->name('sueldo.show');
Route::get('sueldo/{contrato}/create', 'EmpleadosSueldosController@create')->name('sueldo.create');
Route::post('sueldo/{contrato}', 'EmpleadosSueldosController@store')->name('sueldo.store');

/* --- Anticipos --- */
Route::resource('anticipo', 'AnticiposController')->except([
  'create'
]);
Route::get('anticipo/serie/{serie}', 'AnticiposController@serie')->name('anticipo.show.serie');
Route::get('anticipo/serie/{serie}/print', 'AnticiposController@printSerie')->name('anticipo.print.serie');
Route::get('anticipo/{anticipo}/download', 'AnticiposController@download')->name('anticipo.download');
Route::get('anticipo/create/individual', 'AnticiposController@create')->name('anticipo.individual');
Route::get('anticipo/create/masivo', 'AnticiposController@masivo')->name('anticipo.masivo');
Route::post('anticipo/create/masivo', 'AnticiposController@storeMasivo')->name('anticipo.storeMasivo');
Route::post('anticipo/empleados/{contrato}', 'AnticiposController@getEmpleados');
Route::put('anticipo/{anticipo}/status', 'AnticiposController@status')->name('anticipo.status');
Route::delete('anticipo/serie/{serie}', 'AnticiposController@destroySerie')->name('anticipo.destroy.serie');

/* --- Gastos --- */
Route::resource('gasto', 'GastosController');

/* --- Facturas --- */
Route::resource('factura', 'FacturasController');
Route::get('factura/{factura}/download/{adjunto}', 'FacturasController@download')->name('factura.download');

/* --- Transportes --- */
Route::resource('transporte', 'TransportesController')->except([
  'index',
  'show'
]);
/* --- Transportes - Contratos --- */
Route::post('transporte/{transporte}/add/', 'TransportesController@storeContratos')->name('transporte.contrato.store');
Route::delete('transporte/contrato/{contrato}', 'TransportesController@destroyContratos')->name('transporte.contrato.destroy');

/* --- Transportes - Supervisores --- */
Route::delete('transporte/supervisor/{transporte}/{supervisor}', 'TransportesController@destroySupervisor')->name('transporte.supervisor.destroy');

/* --- Inventarios --- */
Route::patch('inventario/clone/{inventario}', 'InventariosController@clone')->name('inventario.clone');

/* --- Clientes --- */
Route::get('cliente/create/{type}', 'ClienteController@create')->name('cliente.create');
Route::post('cliente/store/{type}', 'ClienteController@store')->name('cliente.store');
Route::post('cliente/busqueda/sii', 'ClienteController@busquedaSii')->name('cliente.busqueda.sii');
Route::get('cliente/{cliente}/contactos', 'ClienteController@contactos')->name('cliente.contactos');
Route::get('cliente/{cliente}/direcciones', 'ClienteController@direcciones')->name('cliente.direcciones');
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
Route::patch('contacto/{contacto}/status', 'ContactoController@status')->name('contacto.status');
Route::resource('contacto', 'ContactoController')
      ->only(['edit', 'update', 'destroy']);

/* --- Proveedores --- */
Route::get('proveedor/import/template', 'ProveedorController@importTemplate')->name('proveedor.import.template');
Route::get('proveedor/import', 'ProveedorController@importCreate')->name('proveedor.import.create');
Route::post('proveedor/import', 'ProveedorController@importStore')->name('proveedor.import.store');

Route::get('proveedor/create/{type}', 'ProveedorController@create')->name('proveedor.create');
Route::post('proveedor/store/{type}', 'ProveedorController@store')->name('proveedor.store');
Route::post('proveedor/busqueda/sii', 'ProveedorController@busquedaSii')->name('proveedor.busqueda.sii');
Route::get('proveedor/{proveedor}/contactos', 'ProveedorController@contactos')->name('proveedor.contactos');
Route::resource('proveedor', 'ProveedorController')
      ->except(['create', 'store']);

/* --- Proveedores - Productos --- */
Route::get('proveedor/producto/{proveedor}/create', 'ProveedorProductoController@create')->name('proveedor.producto.create');
Route::post('proveedor/producto/{proveedor}/create', 'ProveedorProductoController@store')->name('proveedor.producto.store');
Route::resource('proveedor/producto', 'ProveedorProductoController')
->names('proveedor.producto')
->except(['index', 'create', 'store']);

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
Route::get('facturacion/create/{cotizacion?}', 'FacturacionController@create')->name('cotizacion.facturacion.create');
Route::resource('facturacion', 'FacturacionController')
      ->names('cotizacion.facturacion')
      ->only(['index', 'store', 'show']);

/* --- Pagos --- */
Route::get('pago/create/{facturacion}', 'PagoController@create')->name('pago.create');
Route::post('pago/create/{facturacion}', 'PagoController@store')->name('pago.store');
Route::get('pago/{pago}/download', 'PagoController@download')->name('pago.download');
Route::resource('pago', 'PagoController')
      ->only(['edit', 'update', 'destroy']);

/* --- Ordenes de compra --- */
Route::get('compra/requerimiento/{requerimiento}', 'OrdenCompraController@requerimiento')->name('compra.requerimiento');
Route::post('compra/requerimiento/{requerimiento}', 'OrdenCompraController@storeRequerimiento');
Route::get('compra/{compra}/pdf', 'OrdenCompraController@pdf')->name('compra.pdf');
Route::get('compra/create/{proveedor?}', 'OrdenCompraController@create')->name('compra.create');
Route::get('compra/{compra}/productos', 'OrdenCompraController@productos')->name('compra.productos');
Route::resource('compra', 'OrdenCompraController')
      ->except('create');

/* --- Ordenes de compra - Productos --- */
Route::resource('compra/producto', 'OrdenCompraProductoController')
      ->names('compra.producto')
      ->only(['edit', 'update', 'destroy']);

/* --- Ordenes de compra - Facturaciones --- */
Route::get('compra/facturacion/create/{compra}', 'OrdenCompraFacturacionController@create')->name('compra.facturacion.create');
Route::post('compra/facturacion/create/{compra}', 'OrdenCompraFacturacionController@store')->name('compra.facturacion.store');
Route::get('compra/facturacion/{codigo}/productos', 'OrdenCompraFacturacionController@getProductos')->name('compra.facturacion.productos');
Route::post('compra/facturacion/{facturacion}/sync', 'OrdenCompraFacturacionController@sync')->name('compra.facturacion.sync');
Route::resource('compra/facturacion', 'OrdenCompraFacturacionController')
      ->names('compra.facturacion')
      ->only(['destroy']);

/* --- Reportes --- */
Route::get('reporte/inventarios', 'ReportesController@inventariosIndex')->name('reporte.inventario.index');
Route::post('reporte/inventarios', 'ReportesController@inventariosGet')->name('reporte.inventario.get');
Route::get('reporte/facturas', 'ReportesController@facturasIndex')->name('reporte.factura.index');
Route::post('reporte/facturas', 'ReportesController@facturasGet')->name('reporte.factura.get');
Route::get('reporte/eventos', 'ReportesController@eventosIndex')->name('reporte.evento.index');
Route::post('reporte/eventos', 'ReportesController@eventosGet')->name('reporte.evento.get');
Route::get('reporte/sueldos', 'ReportesController@sueldosIndex')->name('reporte.sueldo.index');
Route::post('reporte/sueldos', 'ReportesController@sueldosGet')->name('reporte.sueldo.get');
Route::get('reporte/anticipos', 'ReportesController@anticiposIndex')->name('reporte.anticipo.index');
Route::post('reporte/anticipos', 'ReportesController@anticiposGet')->name('reporte.anticipo.get');
Route::get('reporte/transportes', 'ReportesController@transportesIndex')->name('reporte.transporte.index');
Route::post('reporte/transportes', 'ReportesController@transportesGet')->name('reporte.transporte.get');
Route::get('reporte/comidas', 'ReportesController@comidasIndex')->name('reporte.comida.index');
Route::post('reporte/comidas', 'ReportesController@comidasGet')->name('reporte.comida.get');
Route::get('reporte/reemplazos', 'ReportesController@reemplazosIndex')->name('reporte.reemplazo.index');
Route::post('reporte/reemplazos', 'ReportesController@reemplazosGet')->name('reporte.reemplazo.get');
Route::get('reporte/general', 'ReportesController@generalIndex')->name('reporte.general.index');
Route::post('reporte/general', 'ReportesController@generalGet')->name('reporte.general.get');

/* --- Contratos --- */
Route::resource('contrato', 'ContratosController')->only([
  'index',
  'show'
]);
Route::get('contrato/calendar/{contrato}', 'ContratosController@calendar')->name('contrato.calendar');
Route::post('contrato/export/{contrato}', 'ContratosController@exportJornadas')->name('contrato.exportJornadas');
Route::get('contrato/partidas/{contrato}', 'ContratosController@partidas')->name('contrato.partidas');

/* --- Carpetas --- */
Route::get('carpeta/create/{type}/{id}/{carpeta?}', 'CarpetaController@create')->name('carpeta.create');
Route::post('carpeta/create/{type}/{id}/{carpeta?}', 'CarpetaController@store')->name('carpeta.store');
Route::resource('carpeta', 'CarpetaController')
      ->parameters(['carpeta' => 'carpeta'])
      ->except(['create', 'store']);

/* Documentos - Descarga */
Route::get('documento/download/{documento}', 'DocumentosController@download')->name('documento.download');

/* --- Documentos --- */
Route::resource('documento', 'DocumentosController')->only([
  'edit',
  'update',
  'destroy'
]);
Route::get('documento/{type}/{id}/{carpeta?}', 'DocumentosController@create')->name('documento.create');
Route::post('documento/{type}/{id}/{carpeta?}', 'DocumentosController@store')->name('documento.store');

/* --- Etiquetas --- */
Route::resource('etiqueta', 'EtiquetasController')
->parameters([
  'etiqueta' => 'etiqueta',
]);

  /* --- Transportes --- */
Route::resource('transporte', 'TransportesController')->only([
  'index',
  'show'
]);

/* --- Transporte consumo --- */
Route::resource('transporte/consumo', 'TransportesConsumosController')->except([
  'create',
  'store'
]);
Route::get('transporte/consumo/create/{transporte}', 'TransportesConsumosController@create')->name('consumo.create');
Route::post('transporte/consumo/{transporte}', 'TransportesConsumosController@store')->name('consumo.store');

/* --- Inventario V2 ---*/
Route::get('inventario/v2/mass/template', 'InventarioV2Controller@massTemplate')->name('inventario.v2.mass.template');
Route::get('inventario/v2/mass/edit', 'InventarioV2Controller@massEdit')->name('inventario.v2.mass.edit');
Route::post('inventario/v2/mass/edit', 'InventarioV2Controller@massUpdate')->name('inventario.v2.mass.update');
Route::get('inventario/v2/import/template', 'InventarioV2Controller@importTemplate')->name('inventario.v2.import.template');
Route::get('inventario/v2/import', 'InventarioV2Controller@importCreate')->name('inventario.v2.import.create');
Route::post('inventario/v2/import', 'InventarioV2Controller@importStore')->name('inventario.v2.import.store');
Route::get('inventario/v2/export', 'InventarioV2Controller@export')->name('inventario.v2.export');
Route::patch('inventario/v2/{inventario}/ajustar', 'InventarioV2Controller@ajustarStock')->name('inventario.v2.ajustar');
Route::resource('inventario/v2', 'InventarioV2Controller')
->names('inventario.v2')
->parameters([
  'v2' => 'inventario',
]);

/* --- Inventario V2 - Unidad ---*/
Route::resource('unidad', 'UnidadController')
->except([
  'index',
]);

/* --- Inventario V2 - Bodega ---*/
Route::get('bodega/{bodega}/ubicaciones', 'BodegaController@ubicaciones')->name('bodega.ubicaciones');
Route::resource('bodega', 'BodegaController')
->except([
  'index',
]);

/* --- Inventario V2 - Bodega - Ubicación ---*/
Route::get('ubicacion/create/{bodega}', 'UbicacionController@create')->name('ubicacion.create');
Route::post('ubicacion/create/{bodega}', 'UbicacionController@store')->name('ubicacion.store');
Route::resource('ubicacion', 'UbicacionController')
->except([
  'index',
  'create',
  'store',
]);

/* --- Inventario V2 - Ingresos de Stock ---*/
Route::get('inventario/v2/ingreso/create/{inventario}', 'InventarioV2IngresoController@create')->name('inventario.ingreso.create');
Route::post('inventario/v2/ingreso/create/{inventario}', 'InventarioV2IngresoController@store')->name('inventario.ingreso.store');
Route::resource('inventario/v2/ingreso', 'InventarioV2IngresoController')
->names('inventario.ingreso')
->except([
  'index',
  'create',
  'store'
]);

/* --- Inventario V2 - Egresos de Stock ---*/
Route::get('inventario/v2/egreso/create/{inventario}', 'InventarioV2EgresoController@create')->name('inventario.egreso.create');
Route::post('inventario/v2/egreso/create/{inventario}', 'InventarioV2EgresoController@store')->name('inventario.egreso.store');
Route::resource('inventario/v2/egreso', 'InventarioV2EgresoController')
->names('inventario.egreso')
->except([
  'index',
  'create',
  'store'
]);

/* --- Inventario ---*/
Route::resource('inventario', 'InventariosController');
Route::get('inventario/download/{inventario}', 'InventariosController@download')->name('inventario.download');

/* --- Inventario - Entregas ---*/
Route::get('entrega/{inventario}', 'InventariosEntregasController@create')->name('entrega.create');
Route::post('entrega/{inventario}', 'InventariosEntregasController@store')->name('entrega.store');
Route::delete('entrega/{entrega}', 'InventariosEntregasController@destroy')->name('entrega.destroy');
