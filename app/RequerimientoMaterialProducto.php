<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequerimientoMaterialProducto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requerimientos_materiales_productos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'requerimiento_id',
      'inventario_id',
      'tipo_codigo',
      'codigo',
      'nombre',
      'cantidad',
      'added',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'added' => 'boolean',
    ];

    /**
     * Obtener RequerimientoMateria al que pertenece
     */
    public function requerimiento()
    {
      return $this->belongsTo('App\RequerimientoMaterial');
    }

    /**
     * Obtener el Inventario
     */
    public function inventario()
    {
      return $this->belongsTo('App\User', 'solicitante');
    }

    /**
     * Evaluar si el Item es de un Iventario
     * 
     * @return bool
     */
    public function isInventario()
    {
      return !is_null($this->inventario_id);
    }

    /**
     * Evaluar si el Item no es de un Iventario
     * 
     * @return bool
     */
    public function isNotInventrio()
    {
      return !$this->isInventario();
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
     * Evaluar si el producto fue agregado luego de crear el Requerimiento
     * 
     * @return bool
     */
    public function wasAdded()
    {
      return $this->added;
    }
}
