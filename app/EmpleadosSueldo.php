<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Carbon\Carbon;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class EmpleadosSueldo extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados_sueldos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'empleado_id',
      'alcance_liquido',
      'asistencias',
      'anticipo',
      'bono_reemplazo',
      'sueldo_liquido',
      'adjunto',
      'mes_pago',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'created_at',
      'mes_pago',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Sueldo';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'contrato.nombre' => 'Nombre',
      'empleado.usuario.nombreCompleto' => 'Empleado',
      'bono_reemplazo' => 'Bono de reemplazo',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener la url de descarga del adjunto
     * 
     * @return string
     */
    public function getDownloadAttribute()
    {
      return route('sueldo.download', ['sueldo' => $this->id]);
    }

    /**
     * Obtener la Empresa
     */
    public function empresas()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener al Empleado
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener el mes pagado junto con el año
     *
     * @return string
     */
    public function mesPagado()
    {
      setlocale(LC_ALL, 'esp');
      return ucfirst($this->mes_pago->formatLocalized('%B - %Y'));
    }

    /**
     * Obtener el nombre del Empleado
     *
     * @return string
     */
    public function nombreEmpleado()
    {
      return $this->empleado->usuario->nombres . ' ' . $this->empleado->usuario->apellidos;
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function alcanceLiquido()
    {
      return number_format($this->alcance_liquido, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function anticipo()
    {
      return number_format($this->anticipo, 0, ',', '.');
    }
    
    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function bonoReemplazo()
    {
      return number_format($this->bono_reemplazo, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function sueldoLiquido()
    {
      return number_format($this->sueldo_liquido, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function recibido()
    {
      return $this->recibido == 1 ? '<span class="label label-primary">Recibido</span>' : '<span class="label label-default">Pendiente</span>';
    }

    /**
     * Obtener los años de los sueldos registrados por el ID del Contrato proporcionado
     *
     * @param  int|null  $contratoId
     */
    public static function allYears($contratoId = null)
    {
      return self::selectRaw('YEAR(mes_pago) as year')
      ->distinct()
      ->when($contratoId, function ($query, $id) {
        return $query->where('contrato_id', $id);
      })
      ->orderBy('year', 'desc');
    }

    /**
     * Obtener los meses que tienen sueldos registradas en el año proporcionado
     * 
     * @param  int|null  $contratoId
     * @param  int  $year
     */
    public static function getMonthsByYear($contratoId = null, $year)
    {
      return self::selectRaw('MONTH(mes_pago) as month')
        ->distinct()
        ->when($contratoId, function ($query, $id) {
          return $query->where('contrato_id', $id);
        })
        ->whereYear('mes_pago', $year)
        ->orderBy('month', 'desc');
    }

    /**
     * Obtener los sueldos agrupados por mes, del contrato y año proporcionado
     *
     * @param  int|null  $contratoId
     * @param  int  $year
     * @return array
     */
    public static function monthlyGroupedByYear($contratoId = null, $year)
    {
      $months = self::getMonthsByYear($contratoId, $year)
        ->get()
        ->pluck('month')
        ->toArray();

      $sueldosByMonth = [];

      foreach($months as $month){
        $sueldos = self::when($contratoId, function ($query, $id) {
            return $query->where('contrato_id', $id);
          })
          ->whereYear('mes_pago', $year)
          ->whereMonth('mes_pago', $month)
          ->get();

        $dataMonth = [
          'month' => $month,
          'title' => ucfirst(self::getMonthName($month)),
          'sueldos' => $sueldos,
        ];
        $sueldosByMonth[] = (object)$dataMonth;
      }

      return $sueldosByMonth;
    }

    /**
     * Obtener el nombre del mes proporcionado
     * 
     * @param  int  $month
     * @return string
     */
    public static function getMonthName($month)
    {
      $date = Carbon::create(date('Y'), $month, 1);
      return $date->formatLocalized('%B');
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
        'contrato_id',
        'empleado_id',
        'adjunto',
      ])
      ->logAditionalAttributes([
        'contrato.nombre',
        'empleado.usuario.nombreCompleto'
      ]);
    }
}
