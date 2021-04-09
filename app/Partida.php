<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Scopes\{EmpresaScope, LatestScope};

class Partida extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partidas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'tipo',
      'codigo',
      'descripcion',
      'monto',
    ];

    /**
     * Los tipos de Partida admitidos
     * 
     * @var array
     */
    private static $_tipos = [
      'mano de obra',
      'materiales',
      'equipo',
      'maquinaria',
      'otro'
    ];

    /**
     * Total del monto de las Ordenes de Compra
     * 
     * @var null
     */
    private $_totalCompras = null;

    /**
     * Total del monto de Facturas
     * 
     * @var null
     */
    private $_totalFacturas = null;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
      static::addGlobalScope(new LatestScope);
    }

    /**
     * Obtener el total de las Ordenes de Compra
     * 
     * @return float
     */
    public function getTotalComprasAttribute()
    {
      $total = 0;

      if(is_null($this->_totalCompras)){
        foreach ($this->compras as $compra) {
          $total += $compra->total;
        }

        $this->_totalCompras = $total;
      }else{
        $total = $this->_totalCompras;
      }

      return $total;
    }

    /**
     * Obtener el total de las Facturas
     * 
     * @return float
     */
    public function getTotalFacturasAttribute()
    {
      return $this->_totalFacturas = $this->_totalFacturas ?? $this->facturas->sum('valor');
    }

    /**
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener las Ordenes de Compra
     */
    public function compras()
    {
      return $this->hasMany('App\OrdenCompra');
    }

    /**
     * Obtener los Requerimientos de Materiales
     */
    public function requerimientosMateriales()
    {
      return $this->hasMany('App\RequerimientoMaterial');
    }

    /**
     * Obtener las Facturas
     */
    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    /**
     * Obtener los tipos de Partidas admitidos
     *
     * @param  bool  $asList
     * @return array
     */
    public static function getTipos($asList = false)
    {
      return $asList ? join(',', self::$_tipos) : self::$_tipos;
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
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function tipo()
    {
      return ucfirst($this->tipo);
    }

    /**
     * Obtener los tipos de Partidas con la suma de los montos y la cantidad de partidas
     * 
     * @param  int  $contratoId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function tiposByContrato($contratoId)
    {      
      return self::selectRaw(DB::raw('tipo, SUM(monto) as monto, COUNT(id) as count'))
      ->where('contrato_id', $contratoId)
      ->groupBy('tipo')
      ->get();
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function totalCompras()
    {
      return number_format($this->totalCompras, 2, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function totalFacturas()
    {
      return number_format($this->totalFacturas, 2, ',', '.');
    }
}
