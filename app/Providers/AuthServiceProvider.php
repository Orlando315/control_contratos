<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      'App\User' => 'App\Policies\Admin\UserPolicy',
      'App\Contrato' => 'App\Policies\Admin\ContratoPolicy',
      'App\Faena' => 'App\Policies\Admin\FaenaPolicy',
      'App\Empleado' => 'App\Policies\Admin\EmpleadoPolicy',
      'App\EmpleadosContrato' => 'App\Policies\Admin\EmpleadoContratoPolicy',
      'App\EmpleadosSueldo' => 'App\Policies\Admin\EmpleadoSueldoPolicy',
      'App\Etiqueta' => 'App\Policies\Admin\EtiquetaPolicy',
      'App\Factura' => 'App\Policies\Admin\FacturaPolicy',
      'App\Gasto' => 'App\Policies\Admin\GastoPolicy',
      'App\Transporte' => 'App\Policies\Admin\TransportePolicy',
      'App\TransporteConsumo' => 'App\Policies\Admin\TransporteConsumoPolicy',
      'App\Plantilla' => 'App\Policies\Admin\PlantillaPolicy',
      'App\PlantillaDocumento' => 'App\Policies\PlantillaDocumentoPolicy',
      'App\PlantillaVariable' => 'App\Policies\Admin\PlantillaVariablePolicy',
      'App\Cliente' => 'App\Policies\Admin\ClientePolicy',
      'App\Proveedor' => 'App\Policies\Admin\ProveedorPolicy',
      'App\OrdenCompra' => 'App\Policies\Admin\OrdenCompraPolicy',
      'App\Cotizacion' => 'App\Policies\Admin\CotizacionPolicy',
      'App\Facturacion' => 'App\Policies\Admin\FacturacionPolicy',
      'App\Pago' => 'App\Policies\Admin\PagoPolicy',
      'App\Requisito' => 'App\Policies\Admin\RequisitoPolicy',
      'App\Covid19Respuesta' => 'App\Policies\Admin\Covid19RespuestaPolicy',
      'App\CentroCosto' => 'App\Policies\Admin\CentroCostoPolicy',
      'App\InventarioV2' => 'App\Policies\Admin\InventarioV2Policy',
      'App\InventarioV2Ingreso' => 'App\Policies\Admin\InventarioV2IngresoPolicy',
      'App\InventarioV2Egreso' => 'App\Policies\InventarioV2EgresoPolicy',
      'App\Unidad' => 'App\Policies\Admin\UnidadPolicy',
      'App\Bodega' => 'App\Policies\Admin\BodegaPolicy',
      'App\Ubicacion' => 'App\Policies\Admin\UbicacionPolicy',
      'App\Partida' => 'App\Policies\Admin\PartidaPolicy',
      'App\Postulante' => 'App\Policies\Admin\PostulantePolicy',
      'App\Log' => 'App\Policies\Admin\LogPolicy',
      'App\ConfiguracionEmpresa' => 'App\Policies\Admin\ConfiguracionEmpresaPolicy',
      'App\Anticipo' => 'App\Policies\AnticipoPolicy',
      'App\Solicitud' => 'App\Policies\SolicitudPolicy',
      'App\Ayuda' => 'App\Policies\AyudaPolicy',
      'App\RequerimientoMaterial' => 'App\Policies\RequerimientoMaterialPolicy',
      'App\Carpeta' => 'App\Policies\CarpetaPolicy',
      'App\Documento' => 'App\Policies\DocumentoPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
