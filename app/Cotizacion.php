<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Cotizacion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cotizaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id',
      'cliente_id',
      'status',
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
     * Obtener el User que genero la Cotizacion
     */
    public function user()
    {
      return $this->belongsTo('App\Usuario');
    }

    /**
     * Obtener el Cliente
     */
    public function cliente()
    {
      return $this->belongsTo('App\Cliente');
    }

    /**
     * Obtener los productos
     */
    public function productos()
    {
      return $this->hasMany('App\CotizacionProducto');
    }

    /**
     * Codigo para identificar la cotizacion
     *
     * @return string
     */
    public function codigo()
    {
      return str_pad($this->id, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function total()
    {
      return number_format($this->total, 2, ',', '.');
    }
}
