<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class CotizacionProducto extends Model
{
  use LogEvents;

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
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Producto de Cotización';

    /**
     * Eventos que se guardaran en Logs
     * 
     * @var array
     */
    public static $recordEvents = [
      'updated',
      'deleted',
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'inventario.nombre' => 'Inventario',
      'tipo_codigo' => 'Tipo de código',
    ];

    /**
     * Obtener el Inventario
     */
    public function inventario()
    {
      return $this->belongsTo('App\Inventario');
    }

    /**
     * Obtener la Cotizacion
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

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults()
      ->logExcept([
        'inventario_id',
      ])
      ->logAditionalAttributes([
        'inventario.nombre',
      ]);
    }
}
