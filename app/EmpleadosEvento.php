<?php

namespace App;

use Illuminate\Database\Eloquent\{Model, Builder};
use Carbon\Carbon;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class EmpleadosEvento extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados_eventos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empleado_id',
      'reemplazo',
      'valor',
      'inicio',
      'fin',
      'tipo',
      'jornada',
      'comida',
      'pago',
      'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'status' => 'boolean',
    ];

    /**
     * Eventos que se guardaran en Logs
     * 
     * @var array
     */
    public static $recordEvents = [
      'deleted',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Contrato de Empleado';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'empleado.usuario.nombreCompleto' => 'Empleado',
      'status' => 'Estado',
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
        $builder->whereHas('empleado')->with('empleado');
      });
    }

    /**
     * Filtro para obtener solo las Solicitudes aprobados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAsistencias($query)
    {
      return $query->where('tipo', '!=', 1);
    }

    /**
     * Filtro para obtener solo las Solicitudes aprobados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAprobados($query)
    {
      return $query->where('status', true);
    }

    /**
     * Filtro para obtener solo las Solicitudes Pendientes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendientes($query)
    {
      return $query->whereNull('status');
    }

    /**
     * Filtro para obtener solo las Solicitudes Rechazados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRechazados($query)
    {
      return $query->where('status', false);
    }

    /**
     * Establecer la fecna de fin del Contrato.
     *
     * @param  string  $value
     * @return void
     */
    public function getFinAttribute($value)
    {
      if($value){
        $value = new Carbon($value);
        return $value->addDays(1)->toDateString();
      }
    }

    /**
     * Obtener el Empleado que genero el Evento
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado', 'empleado_id');
    }

    /**
     * Evaluar si la Solicitud fue aprobada
     * 
     * @return boolean
     */
    public function isAprobado()
    {
      return $this->status === true;
    }

    /**
     * Evaluar si la Solicitud esta pendiente por aprobar / rechazar
     * 
     * @return boolean
     */
    public function isPendiente()
    {
      return is_null($this->status);
    }

    /**
     * Evaluar si la Solicitud fue rechazada
     * 
     * @return boolean
     */
    public function isRechazado()
    {
      return $this->status === false;
    }

    /**
     * Obtener el status formateado como label
     *
     * @return string
     */
    public function status()
    {
      if(is_null($this->status)){
        return '<small class="label label-default">Pendiente</small>';
      }

      return $this->status ? '<small class="label label-primary">Aprobado</small>' : '<small class="label label-danger">Rechazado</small>';
    }

    /**
     * Obtener el tipo del evento
     *
     * @return string
     */
    public function tipo()
    {
      return $this->eventoData()->titulo;
    }

    /**
     * Obtener los datos del evento segun su tipo
     *
     * @return object
     */
    public function eventoData()
    {
      switch($this->tipo){
        case '1':
          $data = ['titulo'=>'Asistencia', 'color'=>'#00a65a', 'remunerable' => true, 'comida' => true];
          break;
        case '2':
          $data = ['titulo'=>'Licencia médica', 'color'=>'#aa6708', 'remunerable' => false, 'comida' => false];
          break;
        case '3':
          $data = ['titulo'=>'Vacaciones', 'color'=> '#6f5499', 'remunerable' => true, 'comida' => false];
          break;
        case '4':
          $data = ['titulo'=>'Permiso', 'color'=> '#3c8dbc', 'remunerable' => false, 'comida' => false];
          break;
        case '5':
          $data = ['titulo'=>'Permiso no remunerable', 'color'=> '#222d32', 'remunerable' => false, 'comida' => false];
          break;
        case '6':
          $data = ['titulo'=>'Despido', 'color'=> '#ce4844', 'remunerable' => false, 'comida' => false];
          break;
        case '7':
          $data = ['titulo'=>'Renuncia', 'color'=> '#ce4844', 'remunerable' => false, 'comida' => false];
          break;
        case '8':
          $data = ['titulo'=>'Inasistencia', 'color'=> '#4f5b94', 'remunerable' => false, 'comida' => false];
          break;
        case '9':
          $data = ['titulo'=>'Reemplazo', 'color'=> '#001f3f', 'remunerable' => false, 'comida' => false];
          break;
      }
      return (object) $data;
    }

    /**
     * Obtener las fechas de dias feriados
     *
     * @return array
     */
    public static function feriados()
    {
      $feriados = [
        date('Y') . '-01-01',
        date('Y') . '-04-19',
        date('Y') . '-04-20',
        date('Y') . '-05-21',
        date('Y') . '-06-29',
        date('Y') . '-07-16',
        date('Y') . '-08-15',
        date('Y') . '-09-18',
        date('Y') . '-09-19',
        date('Y') . '-09-20',
        date('Y') . '-10-12',
        date('Y') . '-10-31',
        date('Y') . '-11-01',
        date('Y') . '-12-08',
        date('Y') . '-12-25'
      ];

      return $feriados;
    }

    /**
     * Obtener todos los datos de los eventos
     *
     * @return array
     */
    public static function exportAll($inicio, $fin)
    {
      $inicioCarbon = new Carbon($inicio);
      $finCarbon    = new Carbon($fin);

      // Headers para el excel
      $eventosHeaders = [
        'Empleado',
        'Asistencia',
        'Descanso',
        'Licencia médica',
        'Vacaciones',
        'Permiso',
        'Permiso no remunerable',
        'Despido',
        'Renuncia',
        'Inasistencia'
      ];

      $allData = [$eventosHeaders];

      foreach (Empleado::all() as $empleado) {
        $dataRow = array_fill(0,  10, 0);

        $nombre = "{$empleado->rut} | {$empleado->nombres} {$empleado->apellidos}";
        $dataRow[0]  = $nombre;

        $eventos = $empleado->eventos()->select('tipo', 'inicio', 'fin')
                                ->whereBetween('inicio', [$inicio, $fin])
                                ->get();

        foreach ($eventos as $evento) {
          if($evento->fin){
            $eventoStart = new Carbon($evento->inicio);
            $eventoEnd   = new Carbon($evento->fin);
            $diff = $eventoStart->diffInDays($eventoEnd, false);

            $dataRow[($evento->tipo + 1)] += $diff;
          }else{
            $dataRow[($evento->tipo + 1)]++;
          }

        }

        $asistencias = $empleado->countAsisencias($inicio, $fin);

        $dataRow[2] = $asistencias['descanso'];
        $dataRow[1] = ($asistencias['asistencia']- $dataRow[9] ) < 0 ? 0 : ($asistencias['asistencia'] - $dataRow[9]);

        $allData = array_merge($allData, [$dataRow]);
      }
      return $allData;
    }

    /**
     * Obtener el nombre del Empleado que realizo el Reemplazo
     *
     * @return string
     */
    public function nombreReemplazo()
    {
      $nombre = $this->empleado->nombre();
      $route = route('admin.empleado.show', ['empleado' => $this->empleado_id]);

      return "<a href='{$route}'>{$nombre}</a>";
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function valor()
    {
      return number_format($this->valor, 0, ',', '.');
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
