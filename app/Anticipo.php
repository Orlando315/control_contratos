<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\{EmpresaScope, LatestScope};
use Carbon\Carbon;

class Anticipo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anticipos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'empleado_id',
      'serie',
      'fecha',
      'anticipo',
      'bono',
      'descripcion',
      'adjunto',
      'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'status' => 'boolean',
      'solicitud' => 'boolean',
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
      static::addGlobalScope(new LatestScope);
    }

    /**
     * Filtro para obtener solo los Anticipos aprobados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAprobados($query)
    {
      return $query->where('status', true);
    }

    /**
     * Filtro para obtener solo los Anticipos Pendientes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendientes($query)
    {
      return $query->whereNull('status');
    }

    /**
     * Filtro para obtener solo los Anticipos Rechazados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRechazados($query)
    {
      return $query->where('status', false);
    }

    /**
     * Filtro para obtener solo los Anticipos Individuales
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIndividual($query)
    {
      return $query->whereNull('serie');
    }

    /**
     * Filtro para obtener solo los Anticipos en serie (Masivos)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSerie($query)
    {
      return $query->whereNotNull('serie');
    }

    /**
     * Establecer el atributo formateado
     * 
     * @param  string  $value
     * @return void
     */
    public function setFechaAttribute($value)
    {
      $this->attributes['fecha'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Obtener el atributo formateado
     * 
     * @param  string  $value
     * @return string
     */
    public function getFechaAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Obtener directorio para los adjuntos
     * 
     * @param  string  $value
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->empresa_id.'/Empleado'.$this->empleado_id.'/Anticipos';
    }

    /**
     * Obtener el enlace de descarga del adjunto
     * 
     * @return string
     */
    public function getAdjuntoDownloadAttribute()
    {
      return $this->adjunto ? route((Auth::user()->isEmpleado() ? 'anticipos.download' : 'admin.anticipos.download'), ['anticipo' => $this->id]) : null;
    }

    /**
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener el Empleado
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Obtener el atributo formateado
     */
    public function anticipo()
    {
      return number_format($this->anticipo, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     */
    public function bono()
    {
      return number_format($this->bono, 0, ',', '.');
    }

    /**
     * Evaluar si el Anticipo fue aprobado
     * 
     * @return boolean
     */
    public function isAprobado()
    {
      return $this->status === true;
    }

    /**
     * Evaluar si el Anticipo esta pendiente por aprobar / rechazar
     * 
     * @return boolean
     */
    public function isPendiente()
    {
      return is_null($this->status);
    }

    /**
     * Evaluar si el Anticipo fue rechazado
     * 
     * @return boolean
     */
    public function isRechazado()
    {
      return $this->status === false;
    }

    /**
     * Evaluar si el Anticipo fue individual
     * 
     * @return bool
     */
    public function isIndividual()
    {
      return is_null($this->serie);
    }

    /**
     * Evaluar si el Anticipo fue realizado en serie (Masivo)
     * 
     * @return bool
     */
    public function hasSerie()
    {
      return !$this->isIndividual();
    }

    /**
     * Obtener el status formateado como label
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
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function solicitud()
    {
      return $this->solicitud ? '<span class="label label-primary">Sí</span>' : '<span class="label label-default">No</span>';
    }

    /**
     * Generar la serie para los anticipos masivos segun la fecha en que se realice
     * y Id del Contrato proporcionado
     *
     * @param  int  $contratoId
     * @return string
     */
    public static function generateSerie($contratoId)
    {
      $serie = $contratoId.'-'.date('m-Y');
      $count = 1;
      $keepChecking = true;

      while($keepChecking){
        $checkSerie = $serie.'-'.$count;

        // Evaluar si existe en la base de datos
        $keepChecking = self::where('serie', $checkSerie)->exists();
        $count += $keepChecking ? 1 : 0;
      }

      return $serie.'-'.$count;
    }

    /**
     * Obtener los años de los anticipos registrados
     */
    public static function allYears()
    {
      return self::selectRaw('YEAR(fecha) as year')->distinct()->orderBy('year', 'desc');
    }

    /**
     * Obtener los meses que tienen anticipos registradas en el año proporcionado
     * 
     * @param  int  $year
     */
    public static function getMonthsByYear($year)
    {
      return self::selectRaw('MONTH(fecha) as month')->distinct()->whereYear('fecha', $year)->orderBy('month', 'desc');
    }

    /**
     * Obtener los Anticipos agrupados por mes, del año proporcionado
     *
     * @param  int  $year
     * @param  bool|null  $status
     * @return array
     */
    public static function monthlyGroupedByYearAndStatus($year, $status)
    {
      $months = self::getMonthsByYear($year)
        ->individual()
        ->where('status', $status)
        ->get()
        ->pluck('month')
        ->toArray();

      $anticiposByMonth = [];

      foreach($months as $month){
        $anticipos = self::where('status', $status)
          ->individual()
          ->with([
            'contrato',
            'empleado.usuario',
          ])
          ->whereYear('fecha', $year)
          ->whereMonth('fecha', $month)
          ->get();

        $dataMonth = [
          'month' => $month,
          'title' => ucfirst(self::getMonthName($month)),
          'anticipos' => $anticipos,
        ];
        $anticiposByMonth[] = (object)$dataMonth;
      }

      return $anticiposByMonth;
    }

    /**
     * Obtener las series de Anticipos agrouadas por mes, del año proporcionado
     *
     * @param  int  $year
     * @return array
     */
    public static function monthlySeriesGroupedByYear($year)
    {
      $months = self::getMonthsByYear($year)
        ->serie()
        ->get()
        ->pluck('month')
        ->toArray();

      $seriesByMonth = [];

      foreach($months as $month){
        $series = self::selectRaw('contrato_id, serie, fecha, SUM(anticipo) as anticipo, SUM(bono) as bono')
          ->serie()
          ->with('contrato')
          ->whereYear('fecha', $year)
          ->whereMonth('fecha', $month)
          ->get();

        $dataMonth = [
          'month' => $month,
          'title' => ucfirst(self::getMonthName($month)),
          'series' => $series,
        ];
        $seriesByMonth[] = (object)$dataMonth;
      }

      return $seriesByMonth;
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
}
