<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Direccion extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'direcciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'ciudad',
      'comuna',
      'direccion',
      'status',
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
      'status' => 'boolean',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'status' => 'Estatus',
    ];

    /**
     * Filtrar por las direcciones seleccionadas
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelected($query)
    {
      return $query->where('status', true);
    }

    /**
     * Obtener el Parent a la que pertenece
     */
    public function direccionable()
    {
      return $this->morphTo();
    }

    /**
     * Evaluar si la Direccion esta seleccionada
     *
     * @return bool
     */
    public function isSelected()
    {
      return $this->status;
    }

    /**
     * Obtener el tipo de parent
     *
     * @return string
     */
    public function type()
    {
      return $this->direccionable_type == 'App\Cliente' ? 'cliente' : 'proveedor';
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function status()
    {
      return $this->isSelected() ? '<span class="label label-primary">Seleccionada</span>' : '<span class="label label-default">No seleccionada</span>';
    }

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults();
    }
}
