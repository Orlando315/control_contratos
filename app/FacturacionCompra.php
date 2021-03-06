<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class FacturacionCompra extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facturaciones_compras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'orden_compra_id',
      'codigo',
      'emisor',
      'razon_social',
      'documento',
      'folio',
      'fecha',
      'monto',
      'estado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
      'compra',
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'orden_compra_id' => 'Compra',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener la OrdenCompra
     */
    public function compra()
    {
      return $this->belongsTo('App\OrdenCompra', 'orden_compra_id');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function monto()
    {
      return number_format($this->monto, 2, ',', '.');
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function estado()
    {
      return '<span class="label label-default">'.$this->estado.'</span>';
    }

    /**
     * Obtener todas las facturas de la Api filtrando las que ya han sido asociadas
     * en el sistema
     *
     * @return array
     */
    public static function facturasRecibidas()
    {
      $more = true;
      $page = 1;
      $facturas = [];
      $codigosFacturaciones = self::select('codigo')->get()->pluck('codigo')->toArray();

      do{
        [$response, $data] = sii()->facturasRecibidas($page);

        if(!$response){
          return $facturas;
        }

        $pageFacturaciones = collect($data)->whereNotIn('codigo', $codigosFacturaciones);

        $facturas = array_merge($facturas, $pageFacturaciones->all());
        $page++;
        $more = $pageFacturaciones->count() > 0;
      }while($more);

      return $facturas;
    }

    /**
     * Sincronizar informacion con la API
     * 
     * @return bool
     */
    public function syncFacturacion()
    {
      $factura = sii()->consultaFactura($this->codigo);

      if(!$factura){
        return false;
      }

      $this->fill($factura);
      return $this->save();
    }

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults();
    }
}
