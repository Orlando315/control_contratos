<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class ProveedorProducto extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proveedores_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'proveedor_id',
      'inventario_id',
      'nombre',
      'costo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
    ];

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
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Producto de Proveedor';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'proveedor.nombre' => 'Proveedor',
      'inventario.nombre' => 'Inventario',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Inventario
     */
    public function inventario()
    {
      return $this->belongsTo('App\InventarioV2');
    }

    /**
     * Obtener el Proveedor
     */
    public function proveedor()
    {
      return $this->belongsTo('App\Proveedor');
    }

    /**
     * Evaluar si tiene relacion con Inventario
     * 
     * @return bool
     */
    public function hasInventario()
    {
      return !is_null($this->inventario_id);
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function costo()
    {
      return number_format($this->costo, 2, ',', '.');
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
        'empresa_id',
        'proveedor_id',
        'inventario_id',
      ])
      ->logAditionalAttributes([
        'proveedor.nombre',
        'inventario.nombre',
      ]);
    }
}
