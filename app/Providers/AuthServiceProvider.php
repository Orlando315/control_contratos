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
      'App\Inventario' => 'App\Policies\Admin\InventarioPolicy',
      'App\InventarioEntrega' => 'App\Policies\Admin\InventarioEntregaPolicy',
      'App\Transporte' => 'App\Policies\Admin\TransportePolicy',
      'App\TransporteConsumo' => 'App\Policies\Admin\TransporteConsumoPolicy',
      'App\Plantilla' => 'App\Policies\PlantillaPolicy',
      'App\PlantillaDocumento' => 'App\Policies\Admin\PlantillaDocumentoPolicy',
      'App\PlantillaVariable' => 'App\Policies\Admin\PlantillaVariablePolicy',
      'App\Cliente' => 'App\Policies\Admin\ClientePolicy',
      'App\Proveedor' => 'App\Policies\Admin\ProveedorPolicy',
      'App\OrdenCompra' => 'App\Policies\Admin\OrdenCompraPolicy',
      'App\Cotizacion' => 'App\Policies\Admin\CotizacionPolicy',
      'App\Facturacion' => 'App\Policies\Admin\FacturacionPolicy',
      'App\Pago' => 'App\Policies\Admin\PagoPolicy',
      'App\Requisito' => 'App\Policies\Admin\RequisitoPolicy',
      'App\Covid19Respuesta' => 'App\Policies\Admin\Covid19RespuestaPolicy',
      'App\Anticipo' => 'App\Policies\AnticipoPolicy',
      'App\Solicitud' => 'App\Policies\SolicitudPolicy',
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
