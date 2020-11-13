<?php

namespace App;

use Illuminate\Database\Eloquent\{Model, Builder};
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
     * Jornadas admitidas
     * 
     * @var array
     */
    private static $_jornadas = [
      '5x2',
      '4x3',
      '6x1',
      '7x7',
      '10x10',
      '12x12',
      '20x10',
      '7x14',
      '14x14',      
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope('hasEmpleado', function (Builder $builder) {
        $builder->whereHas('empleado');
      });
    }

    /**
     * Filtro para obtener los registros expirados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
      $now = date('Y-m-d H:i:s');
      return $query->whereNotNull('fin')->where('fin', '<=', $now);
    }

    /**
     * Filtro para obtener los registros que estan por vencer faltando los dias especificados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeAboutToExpire($query, $days)
    {
      $now = date('Y-m-d H:i:s');
      $plusDays = date('Y-m-d H:i:s', strtotime("{$now} +{$days} days"));
      return $query->whereNotNull('fin')->whereBetween('fin', [$now, $plusDays])->latestPerEmpleado();
    }

    /**
     * Obtener el ultimo contrato por Empleado
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatestPerEmpleado($query)
    {
      return $query->whereIn('id', function ($query){
        return $query->from(self::getTable())->selectRaw('MAX(id)')->groupBy('empleado_id');
      });
    }

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
     * Obtener las Jornadas disponibles
     * 
     * @return array
     */
    public static function getJornadas()
    {
      return self::$_jornadas;
    }

    /**
     * Obtener los Contratos de que estan por vencer por el type proporcionado
     *
     * @return  object
     */
    public static function groupedAboutToExpire()
    {
      $vencidos = self::expired()->latestPerEmpleado()->count();
      $lessThan3 = self::aboutToExpire(3)->count();
      $lessThan7 = self::aboutToExpire(7)->count();
      $lessThan21 = self::aboutToExpire(21)->count();

      return (object)[
        'vencidos' => $vencidos,
        'lessThan3' => $lessThan3,
        'lessThan7' => $lessThan7,
        'lessThan21' => $lessThan21,
      ];
    }
}
