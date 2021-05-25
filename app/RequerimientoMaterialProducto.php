<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class RequerimientoMaterialProducto extends Model
{
    use LogEvents;

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
    public static $logEventTitle = 'Producto de RM';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'requerimiento_id' => 'Requerimiento',
      'inventario.nombre' => 'Inventario',
      'tipo_codigo' => 'Tipo de código',
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
        'added',
      ])
      ->logAditionalAttributes([
        'inventario.nombre',
      ]);
    }
}
