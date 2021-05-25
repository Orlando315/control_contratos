<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class RequerimientoMaterialFirmante extends Pivot
{
    use LogEvents;

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
      'observacion',
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
     * Eventos que se guardaran en Logs
     * 
     * @var array
     */
    public static $recordEvents = [
      'updated',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Firmante de RM';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'requerimiento_id' => 'Requerimiento',
      'user.nombreCompleto' => 'Usuario firmante',
      'status' => 'Estatus',
      'obligatorio' => '¿Es obligatorio?',
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
     * Obtener el User
     */
    public function user()
    {
      return $this->belongsTo('App\User');
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
      ])
      ->logAditionalAttributes([
        'user.nombreCompleto',
      ]);
    }
}
