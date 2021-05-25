<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class OrdenCompra extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ordenes_compras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'requerimiento_id',
      'user_id',
      'proveedor_id',
      'partida_id',
      'contacto',
      'notas',
      'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'contacto' => 'object',
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
    public static $logEventTitle = 'Orden de compra';

    /**
     * Nombre base de las rutas
     * 
     * @var string
     */
    public static $baseRouteName = 'compra';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'user.nombreCompleto' => 'Generado por',
      'proveedor.nombre' => 'Proveedor',
      'partida.codigo' => 'Partida',
      'status' => 'Estatus'
    ];

    /**
     * Total del precio de todos los productos en la Orden de compra
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
      /**
       * Eliminar toda la informacion relacionada
       */
      static::deleting(function ($model) {
        if(Storage::exists($model->directory)){
          Storage::deleteDirectory($model->directory);
        }
      });
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setContactoAttribute($value)
    {
      $this->attributes['contacto'] = json_encode([
        'id' => $value['id'] ?? null,
        'nombre' => $value['nombre'] ?? null,
        'telefono' => $value['telefono'] ?? null,
        'email' => $value['email'] ?? null,
        'cargo' => $value['cargo'] ?? null,
        'descripcion' => $value['descripcion'] ?? null,
      ]);
    }

    /**
     * Obtener el path del directorio donde se guardaran los archivos
     * 
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return $this->empresa->directory.'/Compras/'.$this->id;
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
      return $this->belongsTo('App\User');
    }

    /**
     * Obtener el Proveedor
     */
    public function proveedor()
    {
      return $this->belongsTo('App\Proveedor');
    }

    /**
     * Obtener los productos
     */
    public function productos()
    {
      return $this->hasMany('App\OrdenCompraProducto');
    }

    /**
     * Obtener la facturacion
     */
    public function facturacion()
    {
      return $this->hasOne('App\FacturacionCompra');
    }

    /**
     * Obtener el Requerimiento de Materiales de donde se genero la Orden de Compra
     */
    public function requerimiento()
    {
      return $this->belongsTo('App\RequerimientoMaterial', 'requerimiento_id', 'id');
    }

    /**
     * Obtener la Partida (De Contratos)
     */
    public function partida()
    {
      return $this->belongsTo('App\Partida');
    }

    /**
     * Evaluar si la Orden de Compra pertenece a un Requerimiento de Materiales
     * 
     * @return bool
     */
    public function hasRequerimiento()
    {
      return !is_null($this->requerimiento_id);
    }

    /**
     * Codigo para identificar la orden de compra
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

    /**
     * Evaluar si la OrdenCompra tiene una Facturacion
     * 
     * @return bool
     */
    public function hasFacturacion()
    {
      return !is_null($this->facturacion);
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function facturacionStatus()
    {
      return $this->hasFacturacion() ? '<span class="label label-primary">Sí</span>' : '<span class="label label-default">No</span>';
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
        'user_id',
        'proveedor_id',
        'partida_id',
      ])
      ->logAditionalAttributes([
        'user.nombreCompleto',
        'proveedor.nombre',
        'partida.codigo',
      ]);
    }
}
