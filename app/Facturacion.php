<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use App\Pago;

class Facturacion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facturaciones';

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
      'cotizacion',
      'pagos',
    ];

    /**
     * Total del precio de todos los productos en la Cotizacion
     * 
     * @var float
     */
    private $_total = null;

    /**
     * Total de todos los pagos en la Cotizacion
     * 
     * @var float
     */
    private $_pagado = null;

    /**
     * Total del pago pendiente en la Cotizacion
     * 
     * @var float
     */
    private $_pendiente = null;

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
      return $this->_total = $this->_total ?? $this->cotizacion->total;
    }

    /**
     * Obtener el total de los pagos
     *
     * @return float
     */
    public function getPagadoAttribute()
    {
      return $this->_pagado = $this->_pagado ?? $this->pagos()->sum('monto');
    }

    /**
     * Obtener el total pendiente por pagar
     *
     * @return float
     */
    public function getPendienteAttribute()
    {
      return (float)number_format($this->total - $this->pagado, 2, '.', '');
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
     * Obtener los Productos
     */
    public function productos()
    {
      return $this->cotizacion->productos();
    }

    /**
     * Obtener los Pagos
     */
    public function pagos()
    {
      return $this->hasMany('App\Pago');
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

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function pagado()
    {
      return number_format($this->pagado, 2, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function pendiente()
    {
      return number_format($this->pendiente, 2, ',', '.');
    }

    /**
     * Evaluar si la Facturacion fue pagada en su totalidad
     * 
     * @return bool
     */
    public function isPaga()
    {
      return $this->pendiente <= 0;
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function status()
    {
      return $this->isPaga() ? '<span class="label label-primary">Paga</span>' : '<span class="label label-default">Pendiente</span>';
    }

    /**
     * Obtener el monto pendiente por pagar menos el monto del Pago especificado
     *
     * @param  \App\Pago  $pago
     * @param  bool  $withFormat
     * @return string
     */
    public function pendienteWithoutPago(Pago $pago, $withFormat = true)
    {
      $pendiente = $this->pendiente + $pago->monto;

      return $withFormat ? number_format($pendiente, 2, ',', '.') : $pendiente;
    }
}
