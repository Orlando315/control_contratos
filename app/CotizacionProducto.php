<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CotizacionProducto extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cotizaciones_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'inventario_id',
      'tipo_codigo',
      'codigo',
      'nombre',
      'descripcion',
      'cantidad',
      'precio',
      'impuesto_adicional',
      'total',
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
     * Obtener el Inventario
     */
    public function inventario()
    {
      return $this->belongsTo('App\Inventario');
    }

    /**
     * Obtener el Cotizacion
     */
    public function cotizacion()
    {
      return $this->belongsTo('App\Cotizacion');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function cantidad()
    {
      return number_format($this->cantidad, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function precio()
    {
      return number_format($this->precio, 2, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function impuesto()
    {
      return number_format($this->impuesto_adicional, 2, ',', '.');
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
     * Evaluar si el producto tiene descripcion
     * 
     * @return bool
     */
    public function hasDescripcion()
    {
      return !is_null($this->descripcion);
    }
}
