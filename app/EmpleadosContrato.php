<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmpleadosContrato extends Model
{
  protected $fillable = [
    'empleado_id',
    'sueldo',
    'inicio',
    'fin',
    'jornada',
    'inicio_jornada',
    'dias_laborables',
    'dias_descanso'
  ];

  public function empleado()
  {
    return $this->belongsTo('App\Empleado', 'empleado_id');
  }

  public function setInicioAttribute($date)
  {
    $this->attributes['inicio'] = $date ? date('Y-m-d', strtotime($date)) : null;
  }

  public function setFinAttribute($date)
  {
    $this->attributes['fin'] = $date ? date('Y-m-d', strtotime($date)) : null;
  }

  public function setInicioJornadaAttribute($date)
  {
    $this->attributes['inicio_jornada'] = $date ? date('Y-m-d', strtotime($date)) : null;
  }

  public function getInicioAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function getFinAttribute($date)
  {
    return $date ? date('d-m-Y', strtotime($date)) : null;
  }

  public function getInicioJornadaAttribute($date)
  {
    return $date ? date('d-m-Y', strtotime($date)) : null;
  }

  public function jornada()
  {
    switch ($this->jornada) {
      case '5x2':
        $dias = ['trabajo'=>5, 'descanso'=>2, 'interval'=>7];
        break;
      case '4x3':
        $dias = ['trabajo'=>4, 'descanso'=>3, 'interval'=>7];
        break;
      case '6x1':
        $dias = ['trabajo'=>6, 'descanso'=>1, 'interval'=>7];
        break;
      case '7x7':
        $dias = ['trabajo'=>7, 'descanso'=>7, 'interval'=>14];
        break;
      case '10x10':
        $dias = ['trabajo'=>10, 'descanso'=>10, 'interval'=>20];
        break;
      case '12x12':
        $dias = ['trabajo'=>12, 'descanso'=>12, 'interval'=>24];
        break;
      case '20x10':
        $dias = ['trabajo'=>20, 'descanso'=>10, 'interval'=>30];
        break;
    }

    return (object)$dias;
  }

  public static function porVencer()
  {
    $dias =  Auth::user()->empresa->configuracion->dias_vencimiento;
    $today = date('Y-m-d H:i:s');
    $less30Days = date('Y-m-d H:i:s', strtotime("{$today} +{$dias} days"));

    return self::has('empleado')->whereNotNull('fin')->whereBetween('fin', [$today, $less30Days])->orderBy('fin', 'desc')->get();
  }
}
