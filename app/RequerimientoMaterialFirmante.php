<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RequerimientoMaterialFirmante extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requerimientos_materiales_firmantes';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'requerimiento_id',
      'user_id',
      'texto',
      'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'obligatorio' => 'boolean',
      'status' => 'boolean',
    ];

    /**
     * Filtrar por registros pendientes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendiente($query)
    {
      return $query->whereNull('status');
    }

    /**
     * Filtrar por registros aprobados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAprobado($query)
    {
      return $query->where('status', true);
    }

    /**
     * Filtrar por registros rechazados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRechazado($query)
    {
      return $query->where('status', false);
    }

    /**
     * Filtrar por las registros obligatorios
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeObligatorio($query)
    {
      return $query->where('obligatorio', true);
    }

    /**
     * Obtener RequerimientoMateria al que pertenece
     */
    public function requerimiento()
    {
      return $this->belongsTo('App\RequerimientoMaterial', 'requerimiento_id');
    }

    /**
     * Evaluar si el Firmante es obligatorio
     * 
     * @return bool
     */
    public function isObligatorio()
    {
      return $this->obligatorio;
    }

    /**
     * Evaluar si el Firmante esta Aprobada
     * 
     * @return bool
     */
    public function isAprobada()
    {
      return $this->status;
    }

    /**
     * Evaluar si el Firmante esta rechazada
     * 
     * @return bool
     */
    public function isRechazada()
    {
      return !$this->isAprobada();
    }

    /**
     * Evaluar si el Firmante esta pendiente
     * 
     * @return bool
     */
    public function isPendiente()
    {
      return is_null($this->status);
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function status()
    {
      if(is_null($this->status)){
        return '<span class="label label-default">Pendiente</span>';
      }

      return $this->status ? '<span class="label label-primary">Aprobado</span>' : '<span class="label label-danger">Rechazado</span>';
    }
}
