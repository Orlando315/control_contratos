<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Scopes\EmpresaScope;
use App\Integrations\FacturacionSii;

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
      'direccion',
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
      'direccion' => 'object',
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
      return $this->empresa->directory.'/Cotizacion/'.$this->id;
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
     * Obtener la facturacion
     */
    public function facturacion()
    {
      return $this->hasOne('App\Facturacion');
    }

    /**
     * Obtener los Pagos de las Facturaciones
     */
    public function pagos()
    {
      return $this->hasManyThrough('App\Pago', 'App\Facturacion');
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

    /**
     * Evaluar si la cotizacion contiene algun producto con impuestos
     * 
     * @return bool
     */
    public function hasImpuestos()
    {
      return $this->productos->contains(function ($producto) {
        return $producto['impuesto_adicional'] > 0;
      });
    }

    /**
     * Evaluar si la Cotizacion tiene una Facturacion
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
     * informacion de los productos para la API de Facturacion Sii
     * 
     * @return array
     */
    private function productosToFactura()
    {
      return $this->productos->map(function ($producto) {
        $data = $producto->only('tipo_codigo', 'codigo', 'nombre', 'cantidad', 'precio', 'impuesto_adicional', 'descripcion');
        $data['impuesto_adicional'] = $data['impuesto_adicional'] ?? 0;
        $data['tiene_descripcion'] = $producto->hasDescripcion();

        return $data;
      })
      ->toArray();
    }

    /**
     * Facturar Cotizacion en la Api de Facturacion Sii
     * 
     * @param  string  $rut
     * @param  string  $dv
     * @return array
     */
    public function facturar($rut, $dv)
    {
      $data = [
        'tiene_impuestos_adicionales' => $this->hasImpuestos(),
        'productos' => $this->productosToFactura(),
      ];

      return (new FacturacionSii)->facturar($rut, $dv, $data);
    }
}
