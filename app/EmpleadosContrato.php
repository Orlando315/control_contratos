<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmpleadosContrato extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados_contratos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empleado_id',
      'sueldo',
      'inicio',
      'fin',
      'jornada',
      'inicio_jornada',
      'descripcion',
    ];

    /**
     * Establecer la fecna de inicio del Contrato.
     *
     * @param  string  $value
     * @return void
     */
    public function setInicioAttribute($value)
    {
      $this->attributes['inicio'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    /**
     * Establecer la fecna de fin del Contrato.
     *
     * @param  string  $value
     * @return void
     */
    public function setFinAttribute($value)
    {
      $this->attributes['fin'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    /**
     * Establecer la fecna de inicio de la jornada del Contrato.
     *
     * @param  string  $value
     * @return void
     */
    public function setInicioJornadaAttribute($value)
    {
      $this->attributes['inicio_jornada'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    /**
     * Obtener la fecha de inicio del Contrato.
     *
     * @param  string  $value
     * @return string
     */
    public function getInicioAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Obtener la fecha de fin del Contrato
     *
     * @param  string  $value
     * @return string
     */
    public function getFinAttribute($value)
    {
      return $value ? date('d-m-Y', strtotime($value)) : null;
    }

    /**
     * Obtener la fecha de inicio de la jornada del Contrato
     *
     * @param  string  $value
     * @return string
     */
    public function getInicioJornadaAttribute($value)
    {
      return $value ? date('d-m-Y', strtotime($value)) : null;
    }

    /**
     * Obtener el Empleado al que pertenece
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado', 'empleado_id');
    }

    /**
     * Obtener informacion de la jornada. La cantidad de dias de trabajo y descanso, y el "intervalo" de dias del turno complleto
     *
     * @return object
     */
    public function jornada()
    {
      switch ($this->jornada) {
        case '5x2':
          $dias = ['trabajo' => 5, 'descanso' => 2, 'interval' => 7];
          break;
        case '4x3':
          $dias = ['trabajo' => 4, 'descanso' => 3, 'interval' => 7];
          break;
        case '6x1':
          $dias = ['trabajo' => 6, 'descanso' => 1, 'interval' => 7];
          break;
        case '7x7':
          $dias = ['trabajo' => 7, 'descanso' => 7, 'interval' => 14];
          break;
        case '10x10':
          $dias = ['trabajo' => 10, 'descanso' => 10, 'interval' => 20];
          break;
        case '12x12':
          $dias = ['trabajo' => 12, 'descanso' => 12, 'interval' => 24];
          break;
        case '20x10':
          $dias = ['trabajo' => 20, 'descanso' => 10, 'interval' => 30];
          break;
        case '7x14':
          $dias = ['trabajo' => 7, 'descanso' => 14, 'interval' => 21];
          break;
        case '14x14':
          $dias = ['trabajo' => 14, 'descanso' => 14, 'interval' => 28];
          break;
      }

      return (object)$dias;
    }

    /**
     * Obtener los Contratos por vencer (menos de 30 dias)
     */
    public static function porVencer()
    {
      $dias =  Auth::user()->empresa->configuracion->dias_vencimiento;
      $today = date('Y-m-d H:i:s');
      $less30Days = date('Y-m-d H:i:s', strtotime("{$today} +{$dias} days"));

      return self::has('empleado')->whereNotNull('fin')->whereBetween('fin', [$today, $less30Days])->orderBy('fin', 'desc')->get();
    }
}
