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

  public function transportes()
  {
    return $this->hasMany('App\TransporteContrato');
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
  public function getPaymentMonth($monthAsNumber = false)
  {
    setlocale(LC_ALL, 'esp');
    $dateLatestSueldo = $this->sueldos()->pluck('created_at')->last();
    $today = new Carbon();
    $dateLatestSueldo = $dateLatestSueldo ? new Carbon($dateLatestSueldo) : $today->copy()->subMonths(1);

    /*
      Si el mes del último pago es igual al mes actual, o mayor,
      devolver false y no permitir registrar los pagos
    */
    if($dateLatestSueldo->isSameMonth($today) || $dateLatestSueldo->gte($today)){
      return false;
    }

    $dateLatestSueldo->addMonths(1);

    return $monthAsNumber ? (int)$dateLatestSueldo->formatLocalized('%m') : ucfirst($dateLatestSueldo->formatLocalized('%B'));
  }

}
