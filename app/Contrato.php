<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Contrato extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contratos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'faena_id',
      'nombre',
      'inicio',
      'fin',
      'valor',
      'descripcion',
      'main',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'main' => 'boolean',
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'faena.nombre' => 'Faena',
      'main' => '¿Es principal?'
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

      return $query->whereNotNull('fin')->whereBetween('fin', [$now, $plusDays]);
    }

    /**
     * Filtro para obtener los registros con el campo main = true
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
      return $query->where('main', true);
    }

    /**
     * Establecer al fecha de inicio
     * 
     * @param  string  $value
     * @return void
     */
    public function setInicioAttribute($value)
    {
      $this->attributes['inicio'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Obtener el atributo formateado
     * 
     * @param  string  $value
     * @return string
     */
    public function getInicioAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Establecer la fecha de Fin
     * 
     * @param  string  $value
     * @return void
     */
    public function setFinAttribute($value)
    {
      $this->attributes['fin'] = date('Y-m-d',strtotime($value));
    }

    /**
     * Obtener el atributo formateado
     * 
     * @param  string  $value
     * @return string
     */
    public function getFinAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Obtener el directorio donde se guardaran Documentos y Carpetas
     * 
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->empresa_id.'/Contrato'.$this->id;
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener los Empleados
     */
    public function empleados()
    {
      return $this->hasMany('App\Empleado');
    }

    /**
     * Obtener los Eventos de los Empleados
     */
    public function empleadosEventos()
    {
      return $this->hasManyThrough('App\empleadosEvento', 'App\Empleado');
    }

    /**
     * Obtener los Documentos
     */
    public function documentos()
    {
      return $this->morphMany('App\Documento', 'documentable');
    }

    /**
     * Obtener los Contratos de los Empleados
     */
    public function empleadosContratos()
    {
      return $this->hasManyThrough('App\EmpleadosContrato', 'App\Empleado');
    }

    /**
     * Obtener los Sueldos de los Empleados
     */
    public function sueldos()
    {
      return $this->hasMany('App\EmpleadosSueldo');
    }

    /**
     * Obtener las Facturas
     */
    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    /**
     * Obtener los Transportes
     */
    public function transportes()
    {
      return $this->belongsToMany('App\Transporte', 'transportes_contratos');
    }

    /**
     * Obtener los Consumos de los Transportes
     */
    public function transportesConsumos()
    {
      return $this->hasMany('App\TransporteConsumo');
    }

    /**
     * Obtener los Anticipos
     */
    public function anticipos()
    {
      return $this->hasMany('App\Anticipo');
    }

    /**
     * Obtener los Inventarios
     */
    public function inventarios()
    {
      return $this->hasMany('App\Inventario');
    }

    /**
     * Obtener las Entregas de Inventario
     */
    public function entregas()
    {
      return InventarioEntrega::with(['inventario:id,nombre', 'realizadoPor:id,nombres,apellidos'])
                                ->join('users', 'inventarios_entregas.entregado', '=', 'users.id')
                                ->join('empleados', 'users.empleado_id', '=', 'empleados.id')
                                ->select('inventario_id', 'realizado','cantidad', 'recibido','inventarios_entregas.created_at', 'empleado_id', 'nombres','apellidos')
                                ->where('empleados.contrato_id', $this->id);
    }

    /**
     * Obtener las PlantillaDocumento (Documetos) en el Contrato
     */
    public function plantillaDocumentos()
    {
      return $this->hasMany('App\PlantillaDocumento');
    }

    /**
     * Obtener las Carpetas
     */
    public function carpetas()
    {
      return $this->morphMany('App\Carpeta', 'carpetable');
    }

    /**
     * Obtener los Requisitos
     */
    public function requisitos()
    {
      return $this->hasMany('App\Requisito');
    }

    /**
     * Obtener la Faena
     */
    public function faena()
    {
      return $this->belongsTo('App\Faena');
    }

    /**
     * Obtener los Inventarios V2 - Egreso
     */
    public function inventariosV2Egreso()
    {
      return $this->hasMany('App\InventarioV2Egreso');
    }

    /**
     * Obtener los RequerimientoMaterial
     */
    public function requerimientosMateriales()
    {
      return $this->hasMany('App\RequerimientoMaterial');
    }

    /**
     * Obtener las Partidas
     */
    public function partidas()
    {
      return $this->hasMany('App\Partida');
    }

    /**
     * Obtener los Requisitos (Documetos/Carpetas) en el Contrato
     */
    public function requisitosWithDocumentos()
    {
      $documentosRequisitos = $this->documentos()->requisito()->distinct('requisito_id')->get();
      $carpetasRequisitos = $this->carpetas()->requisito()->distinct('requisito_id')->get();

      return $this->requisitos()
                  ->ofType('contratos')
                  ->get()
                  ->map(function ($requisito) use ($documentosRequisitos, $carpetasRequisitos) {
                    $requisitos = $requisito->isFolder() ? $carpetasRequisitos : $documentosRequisitos;
                    $requisito->documento = $requisitos->firstWhere('requisito_id', $requisito->id);
                    return $requisito;
                  });
    }

    /**
     * Obtener los Requisitos que aun no tienen un Documento/Carpeta agregado
     *
     * @param  bool  $folder
     */
    public function requisitosFaltantes($folder = false)
    {
      $ids = $this->documentos()->requisito()->distinct('requisito_id')->pluck('requisito_id');
      return $this->requisitos()->ofType('contratos')->where('folder', $folder)->whereNotIn('id', $ids)->get();
    }

    /**
     * Obtener el atributo formateado
     */
    public function valor()
    {
      return number_format($this->valor, 0, ',', '.');
    }

    /**
     * Obtener las jornadas como array para el Calendario
     */
    public function jornadasToCalendar()
    {
      $jornadas = ['trabajo' => [], 'descanso' => []];

      foreach ($this->empleados()->get() as $empleado) {
        $jornada = $empleado->proyectarJornada();

        $jornadas['trabajo'] = array_merge($jornadas['trabajo'], $jornada['trabajo']);
        $jornadas['descanso'] = array_merge($jornadas['descanso'], $jornada['descanso']);
      }

      return $jornadas;
    }

    /**
     * Obtener los Eventos como array para el Calendario
     */
    public function eventsToCalendar($clickable = true, $comparacion = '!=', $tipo = 1, $pago = null)
    {
      $eventos = [];
      foreach($this->empleados()->get() as $empleado){
        $eventos = array_merge($eventos, $empleado->getEventos($clickable, $comparacion, $tipo, $pago));
      }
      return $eventos;
    }

    /**
     * Obtener las jornadas para exportarlas
     *
     * @param  string  $inicio  Fecha de inicio de las Jornadas
     * @param  string  $fin  Fecha de fin de las jornadas
     * @return array
     */
    public function exportJornadas($inicio = null, $fin = null)
    {
      // Tomar la fecha inicial mas baja
      $lowerDateContrato = $this->empleadosContratos()->orderBy('inicio', 'asc')->first();
      $inicio = $inicio ?? $lowerDateContrato->inicio_jornada;

      $lowerStartDate = new Carbon($inicio);

      // Contrato con fecha final mas alta
      $higherDateContrato = $this->empleadosContratos()->orderBy('fin', 'desc')->first();
      if($fin){
        $higherEndDate = new Carbon($fin);
      }else{
        $higherEndDate = new Carbon($higherDateContrato->fin);
        $higherEndDate->addMonths(6);  
      }

      // Periodo desde el inicio del contrato con fecha inicial mas baja, hasta la fecha final calculada
      $periodo = new CarbonPeriod($lowerStartDate, $higherEndDate);

      // Headers para el excel
      $dataHeaders = ['Empleado'];
      $dataRow = array_fill(0, count($periodo) + 1, null);

      foreach($periodo as $date){
        // Headers para el excel
        $dataHeaders[] = $date->format('Y-m-d');
      }

      $allData = [$dataHeaders];

      foreach ($this->empleados()->get() as $empleado) {
        $nombre = "{$empleado->usuario->rut} | {$empleado->nombre()}";

        $jornadas    = $empleado->proyectarJornadaAsArray($dataRow, $dataHeaders, $inicio, $fin);
        $jornadas[0] = $nombre;
        $eventos     = $empleado->getEventosAsArray($dataRow, $dataHeaders, $inicio, $fin);
        $eventos[0]  = $nombre;

        $allData = array_merge($allData, [$jornadas, $eventos]);
      }
      return $allData;
    }

    /**
    * Obtener el mes a pagar
    * 
    * @param  bool $monthAsDate
    * @return int | string
    */
    public function getPaymentMonth($monthAsDate = false)
    {
      setlocale(LC_ALL, 'esp');
      $dateLatestSueldo = $this->sueldos()->pluck('mes_pago')->last();
      $today = new Carbon();

      // Si no hay pagos registrados. Se toma la fecha de Inicio del contrato
      $dateToPay = new Carbon($dateLatestSueldo ?? $this->inicio);

      if(!$dateLatestSueldo){
        $dateToPay->subMonths(1);
      }

      /*
        Si el mes del último pago es igual al mes actual, o mayor,
        devolver false y no permitir registrar los pagos
      */
      if($dateToPay->isSameMonth($today) || $dateToPay->gte($today)){
        return false;
      }

      // Se pagara el me siguiente a la fecha del ultimo pago
      $dateToPay->addMonths(1);

      // Si no hay pagos registrados. La fecha inicial a pagar sera la misma que la fecha de Inicio del contrato.
      // Sino, se toma el primer dia del mes.
      $startPaymentDate = $dateLatestSueldo ? $dateToPay->startOfMonth()->format('Y-m-d') : $dateToPay->format('Y-m-d');

      $stringMonth = ucfirst($dateToPay->formatLocalized('%B - %Y')).' ('.$startPaymentDate.' - '.$dateToPay->endOfMonth()->format('Y-m-d').')';

      return $monthAsDate ? $dateToPay->startOfMonth() : $stringMonth;
    }

    /**
     * Obtener el total a pagar
     *
     * @return float
     */
    public function getTotalAPagar()
    {
      $total = 0;

      foreach ($this->empleados()->get() as $empleado){
        $total += $empleado->getSueldoLiquido();
      }

      return $total;
    }

    /**
     * Obtener todos los Eventos de los Empleados
     *
     * @param  string  $inicio
     * @param  string  $fin
     * @return array
     */
    public function getAllEventsData($inicio, $fin)
    {
      $inicioCarbon = new Carbon($inicio);
      $finCarbon    = new Carbon($fin);

      $totalDays = $inicioCarbon->diffInDays($finCarbon, false) + 1;

      // Headers
      $eventosHeaders = [
        'Empleado',
        'Balance',
        'Asistencia',
        'Descanso',
        'Licencia médica',
        'Vacaciones',
        'Permiso',
        'Permiso no remunerable',
        'Despido',
        'Renuncia',
        'Inasistencia',
        'Reemplazo'
      ];

      $allData = [$eventosHeaders];

      foreach ($this->empleados()->with('usuario')->get() as $empleado) {
        $dataRow = array_fill(0,  12, 0);

        $nombre = "{$empleado->usuario->rut} | {$empleado->usuario->nombres} {$empleado->usuario->apellidos}";
        $dataRow[0]  = $nombre;

        $eventos = $empleado->eventos()
                            ->select('tipo', 'inicio', 'fin')
                            ->where(function($query) use ($inicio, $fin){
                              $query->where(function ($queryWhereNull) use ($inicio, $fin){
                                      $queryWhereNull->whereNull('fin')
                                                    ->Where([
                                                      ['inicio', '>=', $inicio],
                                                      ['inicio', '<=', $fin]
                                                    ]);
                                    })
                                    ->orWhere(function($queryWhereNotNull) use ($inicio, $fin){
                                      $queryWhereNotNull->whereNotNull('fin')
                                                        ->where([
                                                          ['inicio', '<=', $inicio],
                                                          ['fin', '>=', $inicio]
                                                        ])
                                                        ->orWhere([
                                                          ['inicio', '>=', $inicio],
                                                          ['inicio', '<=', $fin]
                                                        ]);
                                    });
                            })
                            ->where('tipo', '!=', 1)
                            ->get();

        $proyeccion = $empleado->countAsisencias($inicio, $fin);

        $inasistencias = $empleado->eventos()
                                  ->where([
                                    ['tipo', 1],
                                    ['pago', 0]
                                  ])
                                  ->whereBetween('inicio', [$inicio, $fin])
                                  ->count();

        $sumEventos = 0;

        foreach ($eventos as $evento) {
          if($evento->fin){
            $eventoStart = new Carbon($evento->inicio);
            $eventoEnd   = new Carbon($evento->fin);

            if($inicioCarbon->gte($eventoStart)){
              $eventoStart = $inicioCarbon;
            }

            if($eventoEnd->gte($finCarbon)){
              $eventoEnd = $finCarbon;
            }

            $diff = $eventoStart->diffInDays($eventoEnd, false);

            $dataRow[($evento->tipo + 2)] += $diff;

            // Las vacaciones no se toman en cuenta
            $sumEventos += $evento->tipo != 3 ? $diff : 0;
          }else{
            $dataRow[($evento->tipo + 2)]++;
            $sumEventos += $evento->tipo != 3 ? 1 : 0;
          }
        }

        $asistencias = ($proyeccion['asistencia'] - $inasistencias) < 0 ? 0 : ($proyeccion['asistencia'] - $inasistencias);
        $descanso    = $proyeccion['descanso'];

        $dataRow[2]  = $asistencias;
        $dataRow[3]  = $descanso;
        $dataRow[10] = $inasistencias;
        $balance     = ($asistencias + $descanso) - $sumEventos;
        $dataRow[1]  = $balance < 0 ? 0 : $balance;

        $allData = array_merge($allData, [$dataRow]);
      }

      return ['data' => $allData, 'days' => $totalDays];
    }

    /**
     * Obtener los Contratos de que estan por vencer
     *
     * @return  object
     */
    public static function groupedAboutToExpire()
    {
      $vencidos = self::expired()->count();
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

    /**
     * Evaluar si el Contrato esta seleccionado como Principal
     * 
     * @return bool
     */
    public function isMain()
    {
      return $this->main;
    }

    /**
     * Establecer el Contrato como Principal
     */
    public function setAsMain()
    {
      if($this->isMain()){
        return false;
      }

      $this->empresa->contratos()->main()->update(['main' => false]);
      $this->main = true;
      $this->save();
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function principal()
    {
      return $this->isMain() ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
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
        'empresa_id',
        'faena_id',
      ])
      ->logAditionalAttributes([
        'faena.nombre'
      ]);
    }
}
