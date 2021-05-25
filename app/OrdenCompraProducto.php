<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class OrdenCompraProducto extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ordenes_compras_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'producto_id',
      'inventario_id',
      'tipo_codigo',
      'codigo',
      'nombre',
      'descripcion',
      'cantidad',
      'precio',
      'afecto_iva',
      'impuesto_adicional',
      'precio_total',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'afecto_iva' => 'boolean',
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
    public static $logEventTitle = 'Producto de OC';

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
      'afecto_iva' => '¿Es afecto a IVA?',
    ];

    /**
     * Establecer el valor del atributo
     *
     * @param  string  $value
     * @return void
     */
    public function setUrgenciaAttribute($value)
    {
      $this->attributes['urgencia'] = ($value != 'normal' && $value != 'urgente') ? 'normal' : $value;
    }

    /**
     * Establecer el valor del atributo
     *
     * @param  string  $value
     * @return void
     */
    public function setImpuestoAdicionalAttribute($value)
    {
      $this->attributes['impuesto_adicional'] = round($value, 2);
    }

    /**
     * Establecer el valor del atributo
     *
     * @param  string  $value
     * @return void
     */
    public function setPrecioTotalAttribute($value)
    {
      $this->attributes['precio_total'] = round($value, 2);
    }

    /**
     * Establecer el valor del atributo
     *
     * @param  string  $value
     * @return void
     */
    public function setTotalAttribute($value)
    {
      $this->attributes['total'] = round($value, 2);
    }

    /**
     * Obtener el Inventario
     */
    public function inventario()
    {
      return $this->belongsTo('App\InventarioV2');
    }

    /**
     * Obtener la Orden de compra
     */
    public function ordenCompra()
    {
      return $this->belongsTo('App\OrdenCompra');
    }

    /**
     * Evaluar si el Producto es afecto a iva
     * 
     * @return boolean
     */
    public function isAfectoIva()
    {
      return $this->afecto_iva;
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
    public function precioTotal()
    {
      return number_format($this->precio_total, 2, ',', '.');
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
        'impuesto_adicional',
        'precio_total',
      ])
      ->logAditionalAttributes([
        'inventario.nombre',
      ]);
    }
}
