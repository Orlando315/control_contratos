<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Facturacion extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Facturaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'cotizacion_id',
      'sii_factura_id',
      'rut',
      'dv',
      'firma',
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
      'cotizacion'
    ];

    /**
     * Total del precio de todos los productos en la Cotizacion
     * 
     * @var null|float
     */
    private $_total = null;


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
     * Obtener el total del costo de los productos
     *
     * @return float
     */
    public function getTotalAttribute()
    {
      return $this->_total = $this->_total ?? $this->productos()->sum('total');
    }

    /**
     * Obtener el User que genero la Cotizacion
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener la Cotizacion
     */
    public function cotizacion()
    {
      return $this->belongsTo('App\cotizacion');
    }

    /**
     * Obtener el Cliente
     */
    public function cliente()
    {
      return $this->cotizacion->cliente();
    }

    /**
     * Obtener los productos
     */
    public function productos()
    {
      return $this->cotizacion->productos();
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function total()
    {
      return number_format($this->cotizacion->total, 2, ',', '.');
    }
}
