<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Contacto extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre',
      'email',
      'telefono',
      'cargo',
      'descripcion',
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
    public $attributesTitle = [
      'status' => 'Estatus',
    ];

    /**
     * Filtrar por los contactos seleccionadas
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
    public function contactable()
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
      return $this->contactable_type == 'App\Cliente' ? 'cliente' : 'proveedor';
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function status()
    {
      return $this->isSelected() ? '<span class="label label-primary">Seleccionado</span>' : '<span class="label label-default">No seleccionado</span>';
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
