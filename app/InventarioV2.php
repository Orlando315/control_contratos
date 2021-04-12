<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Auth;

class InventarioV2 extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventarios_v2';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'unidad_id',
      'bodega_id',
      'nombre',
      'descripcion',
      'tipo_codigo',
      'codigo',
      'foto',
      'stock',
      'stock_minimo',
    ];

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
     * Obtener el Directorio
     *
     * @param  string  $value
     * @return string
     */
    public function getDirectoryAttribute($value)
    {
      return 'Empresa' . Auth::user()->empresa->id . '/InventariosV2/' . $this->id;
    }

    /**
     * Obtener la url de la foto
     *
     * @param  string  $value
     * @return string
     */
    public function getFotoUrlAttribute($value)
    {
      return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener la Unidad
     */
    public function unidad()
    {
      return $this->belongsTo('App\Unidad');
    }

    /**
     * Obtener las Categorias/Etiquetas
     */
    public function categorias()
    {
      return $this->belongsToMany('App\Etiqueta', 'inventarios_categorias', 'inventario_id', 'etiqueta_id');
    }

    /**
     * Obtener los Ingresos de Stocks
     */
    public function ingresos()
    {
      return $this->hasMany('App\InventarioV2Ingreso', 'inventario_id', 'id');
    }

    /**
     * Obtener los Egresos de Stocks
     */
    public function egresos()
    {
      return $this->hasMany('App\InventarioV2Egreso', 'inventario_id', 'id');
    }

    /**
     * Obtener la Bodega
     */
    public function bodega()
    {
      return $this->belongsTo('App\Bodega');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function stock()
    {
      return number_format($this->stock, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function stockMinimo()
    {
      return number_format($this->stock_minimo, 0, ',', '.');
    }

    /**
     * Sumar la cantidad proporcionada al stock
     *
     * @param  float  $cantidad
     * @return void
     */
    public function addStock($cantidad)
    {
      $this->increment('stock', $cantidad);
    }

    /**
     * Restar la cantidad proporcionada al Stock
     *
     * @param  float  $cantidad
     * @return void
     */
    public function subStock($cantidad)
    {
      $this->decrement('stock', $cantidad);
    }

    /**
     * Reemplazar el Stock disponible con la cantidad proporcionada
     *
     * @param  float  $cantidad
     * @return void
     */
    public function replaceStock($cantidad)
    {
      $this->stock = $cantidad;
      $this->save();
    }

    /**
     * Actualizar el Stock con la cantidad proporcionada
     *
     * @param  float  $cantidad
     * @param  bool  $isIngreso
     * @return void
     */
    public function updateStock($cantidad, $isIngreso = true)
    {
      $convert = $isIngreso ? -1 : 1;

      $this->addStock($cantidad * $convert);
    }
}
