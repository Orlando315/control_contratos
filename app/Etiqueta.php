<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Etiqueta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'etiquetas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'etiqueta'
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
     * Obtener La Factuas que pertenecen a la Etiqueta
     */
    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    /**
     * Obtener los Gastos que pertenecen a la Etiqueta
     */
    public function gastos()
    {
      return $this->hasMany('App\Gasto');
    }

    /**
     * Obtener las Categorias/Etiquetas
     */
    public function inventariosV2()
    {
      return $this->belongsToMany('App\InventarioV2', 'inventarios_categorias', 'etiqueta_id', 'inventario_id');
    }
}
