<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class OrdenCompra extends Model
{
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
      'user_id',
      'proveedor_id',
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
}
