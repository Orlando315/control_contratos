<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class EmpleadosBanco extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados_bancos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre',
      'tipo_cuenta',
      'cuenta'
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Datos bancarios de Empleado';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'empleado.usuario.nombreCompleto' => 'Empleado',
      'nombre' => 'Nombre del banco',
      'tipo_cuenta' => 'Tipo de cuenta',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Obtener el Empleado al que pertenece
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado', 'empleado_id');
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
      ])
      ->logAditionalAttributes([
        'empleado.usuario.nombreCompleto'
      ]);
    }
}
