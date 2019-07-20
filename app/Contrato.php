<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Contrato extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }
  
  protected $fillable = [
    'empresa_id',
    'nombre',
    'inicio',
    'fin',
    'valor'
  ];

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function empleados()
  {
    return $this->hasMany('App\Empleado');
  }

  public function empleadosEventos()
  {
    return $this->hasManyThrough('App\empleadosEvento', 'App\Empleado');
  }

  public function documentos()
  {
    return $this->hasMany('App\Documento');
  }

  public function empleadosContratos()
  {
    return $this->hasManyThrough('App\EmpleadosContrato', 'App\Empleado');
  }

  public function sueldos()
  {
    return $this->hasMany('App\EmpleadosSueldo');
  }

  public function facturas()
  {
    return $this->hasMany('App\Factura');
  }

  public function transportes()
  {
    return $this->belongsToMany('App\Transporte', 'transportes_contratos');
  }

  public function transportesConsumos()
  {
    return $this->hasMany('App\TransporteConsumo');
  }

  public function anticipos()
  {
    return $this->hasMany('App\Anticipo');
  }

  public function inventarios()
  {
    return $this->hasMany('App\Inventario');
  }

  public function entregas()
  {
    return InventarioEntrega::with(['inventario:id,nombre', 'realizadoPor:id,nombres,apellidos'])
                              ->join('users', 'inventarios_entregas.entregado', '=', 'users.id')
                              ->join('empleados', 'users.empleado_id', '=', 'empleados.id')
                              ->select('inventario_id', 'realizado','cantidad', 'recibido','inventarios_entregas.created_at', 'empleado_id', 'nombres','apellidos')
                              ->where('empleados.contrato_id', $this->id);
  }

  public function valor()
  {
    return number_format($this->valor, 0, ',', '.');
  }

  public function setInicioAttribute($date)
  {
    $this->attributes['inicio'] = date('Y-m-d', strtotime($date));
  }

  public function getInicioAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function setFinAttribute($date)
  {
    $this->attributes['fin'] = date('Y-m-d',strtotime($date));
  }

  public function getFinAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

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

  public function eventsToCalendar($clickable = true, $comparacion = '!=', $tipo = 1, $pago = null)
  {
    $eventos = [];
    foreach($this->empleados()->get() as $empleado){
      $eventos = array_merge($eventos, $empleado->getEventos($clickable, $comparacion, $tipo, $pago));
    }
    return $eventos;
  }

  public function comidasToCalendar()
  {
    $comidas = [];
    foreach($this->empleados()->get() as $empleado){
      $search = $empleado->getComidas();
      $data = [];

      foreach($search as $comida){
        $data[] = [
          'resourceId' => $empleado->id,
          'id' => 'C'.$comida->id,
          'className' => 'clickableEvent',
          'title' => 'Comida',
          'start' => $comida->inicio,
          'end' => null,
          'color' => '#001f3f'
        ];
      }// Foreach Comidas

      $comidas = array_merge($comidas, $data);
    }// Foreach Empleados

    return $comidas;
  }


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
      $nombre = "{$empleado->rut} | {$empleado->nombres} {$empleado->apellidos}";

      $jornadas    = $empleado->proyectarJornadaAsArray($dataRow, $dataHeaders);
      $jornadas[0] = $nombre;
      $eventos     = $empleado->getEventosAsArray($dataRow, $dataHeaders);
      $eventos[0]  = $nombre;

      $allData = array_merge($allData, [$jornadas, $eventos]);
    }
    return $allData;
  }

  /**
  * Obtener el mes a pagar
  * @param  Bool $monthAsNumber
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

  public function getTotalAPagar()
  {
    $total = 0;

    foreach ($this->empleados()->get() as $empleado){
      $total += $empleado->getSueldoLiquido();
    }

    return $total;
  }

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

}
