<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Postulante extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'postulantes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empleado_id',
      'nombres',
      'apellidos',
      'rut',
      'telefono',
      'email',
    ];

    /**
     * Obtener el nombre completo del Postulante
     */
    public function getNombreCompletoAttribute()
    {
      return trim($this->nombres.' '.$this->apellidos);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el nombre completo del Usuario
     * 
     * @return string
     */
    public function nombre()
    {
      return $this->nombres.' '.$this->apellidos;
    }

    /**
     * Obtener las PlantillaDocumento (Documetos)
     */
    public function plantillaDocumentos()
    {
      return $this->hasMany('App\PlantillaDocumento');
    }

    /**
     * Migrar Documentos del Postulante al Empleado
     * 
     * @param  \App\Empleado  $empleado
     * @return void
     */
    public function migrateDocumentos(Empleado $empleado)
    {
      $this->plantillaDocumentos()->update([
        'contrato_id' => $empleado->contrato_id,
        'empleado_id' => $empleado->id,
        'postulante_id' => null,
      ]);
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
        'empleado_id',
      ]);
    }
}
