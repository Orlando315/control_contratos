<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\EmpresaScope;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Empleado extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $table = 'empleados';

  protected $fillable = [
    'sexo',
    'fecha_nacimiento',
    'direccion',
    'talla_camisa',
    'talla_zapato',
    'talla_pantalon'
  ];

  protected $guarded = ['empresa_id'];

  public function setFechaNacimientoAttribute($date)
  {
    $this->attributes['fecha_nacimiento'] = $date ? date('Y-m-d', strtotime($date)) : null;
  }

  public function getFechaNacimientoAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function usuario()
  {
    return $this->hasOne('App\Usuario');
  }

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }

  public function contratos()
  {
    return $this->hasMany('App\EmpleadosContrato');
  }

  public function banco()
  {
    return $this->hasOne('App\EmpleadosBanco');
  }

  public function documentos()
  {
    return $this->hasMany('App\Documento');
  }

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function eventos()
  {
    return $this->hasMany('App\EmpleadosEvento');
  }

  public function anticipos()
  {
    return $this->hasMany('App\Anticipo');
  }

  public function latestAnticipo()
  {
    return $this->hasOne('App\Anticipo')->select(['empleado_id', 'anticipo'])->latest();
  }

  public function reemplazos(){
    return $this->hasMany('App\EmpleadosEvento', 'reemplazo')->where('tipo', 9);
  }

  public function sueldos()
  {
    return $this->hasMany('App\EmpleadosSueldo');
  }

  public function entregas()
  {
    return $this->usuario()->first()->hasMany('App\InventarioEntrega', 'entregado')->with('Inventario:id,nombre');
  }

  public function proyectarJornada()
  {
    $events = ['trabajo' => [], 'descanso'=>[]];

    foreach ($this->contratos()->get() as $contrato){
      
      $contratoStart = new Carbon($contrato->inicio_jornada);
      // Si el contrato no tiene fecha de fin, se proyectan 3 años desde la fecha de inicio
      $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(3);
      
      //Diferencia en dias desde el inicio hasta el fin del contrato      
      $diffInDays = $contratoStart->diffInDays($contratoEnd);
      $jornada = $contrato->jornada();
      // Intervalos a iterar para generar los bloquees de trabajo + descanso
      $interval = (int) ceil($diffInDays / $jornada->interval);
      $endDifInDays = 1;

      for ($i=0; $i < $interval; $i++){
        $endJornada = $contratoStart->copy()->addDays($jornada->trabajo);

        if($i == ($interval - 1)){
          $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

          // Si fecha final de la jornada es menor a la fecha final de contrato
          // se le restan la diferencia en dias a sa jornada
          if($endDifInDays < 0){
            $endJornada = $endJornada->subDays(($endDifInDays * -1));
          }
        }

        $trabajo = [
          'resourceId' => $this->id,
          'title' => 'Trabajo ' . $contrato->jornada,
          'start' => $contratoStart->toDateString(),
          'end' => $endJornada->toDateString(),
          'allday' => true
        ];
        // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
        $contratoStart->addDays($jornada->trabajo);

        $events['trabajo'][] = $trabajo;
        
        if($endDifInDays < 0){
          continue;
        }

        $endJornada = $contratoStart->copy()->addDays($jornada->descanso);

        if($i == ($interval - 1)){
          $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

          // Si fecha final de la jornada es menor a la fecha final de contrato
          // se le restan la diferencia en dias a sa jornada
          if($endDifInDays < 0){
            $endJornada = $endJornada->subDays(($endDifInDays * -1));
          }
        }

        $descanso = [
          'resourceId' => $this->id,
          'title' => 'Descanso ' . $contrato->jornada,
          'start' => $contratoStart->toDateString(),
          'end' => $endJornada->toDateString(),
          'allday' => true,
        ];
        // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
        $contratoStart->addDays($jornada->descanso);
        $events['descanso'][] = $descanso;
      }// For invertal

    }//Foreach contratos

    return $events;
  }

  public function getEventos($clickable = true, $comparacion = '!=', $tipo = 1, $pago = null)
  {
    $eventos = [];
    $search = $this->eventos()
                    ->where(function ($query) use ($comparacion, $tipo, $pago){
                      $query->where('tipo', $comparacion, $tipo)
                            ->when($pago, function($queryWhen) use ($pago){
                            $queryWhen->where('pago', $pago);
                          });
                    })
                    ->get();

    $className = $clickable ? 'clickableEvent' : '';

    foreach($search as $evento){
      $data = $evento->eventoData();

      $eventos[] = [
        'resourceId' => $evento->empleado_id,
        'id' => $evento->id,
        'className' => $className,
        'title' => $data->titulo,
        'start' => $evento->inicio,
        'end' => $evento->fin,
        'color' => $data->color
      ];
    }

    return $eventos;
  }

  public function getFeriados()
  {
    $feriados = [];

    foreach (EmpleadosEvento::feriados() as $feriado) {
      $feriados[] = [
                      'title' => 'Feriado',
                      'start' => $feriado,
                      'fillday' => true
                    ];
    }

    return $feriados;
  }

  public function getDataAsArray($inicio = null, $fin = null)
  {
    // Contratos
    $lastContrato  = $this->contratos->last();
    $firstContrato = $this->contratos->first();
    
    $carbonInicioLastContrato = new Carbon($lastContrato->inicio_jornada);
    // Si el lastContrato no tiene fecha de fin, se proyectan 3 años desde la fecha de inicio
    $finLastContrato = $lastContrato->fin ?? $carbonInicioLastContrato->addYears(3);
    // Periodo desde el inicio del 1er contrato, hasta el fin del ultimo

    $inicio = $inicio ?? $firstContrato->inicio_jornada;
    $fin    = $fin ?? $finLastContrato;

    $periodo = new CarbonPeriod($inicio, $fin);

    // Headers para el excel
    $dataHeaders = ["{$this->rut} | {$this->nombres} {$this->apellidos}"];
    $dataRow = array_fill(0, count($periodo) + 1, null);

    foreach($periodo as $date){
      // Headers para el excel
      $dataHeaders[] = $date->format('Y-m-d');
    }

    $jornadas = $this->proyectarJornadaAsArray($dataRow, $dataHeaders);
    $eventos  = $this->getEventosAsArray($dataRow, $dataHeaders);
    $feriados = $this->getFeriadosAsArray($dataRow, $dataHeaders);

    return [$dataHeaders, $jornadas, $eventos, $feriados];
  }

  public function proyectarJornadaAsArray($dataRow, $dataHeaders)
  {
    foreach ($this->contratos()->get() as $contrato){
      
      $contratoStart = new Carbon($contrato->inicio_jornada);
      // Si el contrato no tiene fecha de fin, se proyectan 3 años desde la fecha de inicio
      $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(3);

      // Diferencia en dias desde el inicio hasta el fin del contrato      
      $diffInDays = $contratoStart->diffInDays($contratoEnd);
      $jornada = $contrato->jornada();
      // Intervalos a iterar para generar los bloquees de trabajo + descanso
      $interval = (int) ceil($diffInDays / $jornada->interval);
      $endDifInDays = 1;

      for ($i=0; $i < $interval; $i++){
        $endJornada = $contratoStart->copy()->addDays($jornada->trabajo);

        if($i == ($interval - 1)){
          $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

          // Si fecha final de la jornada es menor a la fecha final de contrato
          // se le restan la diferencia en dias a sa jornada
          if($endDifInDays < 0){
            $endJornada = $endJornada->subDays(($endDifInDays * -1));
          }
        }

        // Encuentro el index de la fecha de inicio de la jornada
        $dataStart = array_search($contratoStart->toDateString(), $dataHeaders);
        if($dataStart !== false){
          // Crea un array temporal desde el index encontrado y la cantidad de dias de trabajo
          $arrayTempData = array_fill($dataStart, $jornada->trabajo, 'Trabajo ' . $contrato->jornada);
          // Reemplaza los valores del array vacio con los valores de la jornada
          $dataRow = array_replace($dataRow, $arrayTempData);
        }
        // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
        $contratoStart->addDays($jornada->trabajo);
        
        if($endDifInDays < 0){
          continue;
        }

        $endJornada = $contratoStart->copy()->addDays($jornada->descanso);

        if($i == ($interval - 1)){
          $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

          // Si fecha final de la jornada es menor a la fecha final de contrato
          // se le restan la diferencia en dias a sa jornada
          if($endDifInDays < 0){
            $endJornada = $endJornada->subDays(($endDifInDays * -1));
          }
        }

        // Encuentro el index de la fecha de inicio de la jornada
        $dataStart = array_search($contratoStart->toDateString(), $dataHeaders);
        if($dataStart !== false){
          // Crea un array temporal desde el index encontrado y la cantidad de dias de descanso
          $arrayTempData = array_fill($dataStart, $jornada->descanso, 'Descanso ' . $contrato->jornada);
          // Reemplaza los valores del array vacio con los valores de la jornada
          $dataRow = array_replace($dataRow, $arrayTempData);
        }
        // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
        $contratoStart->addDays($jornada->descanso);
      }// For invertal

    }// Foreach contratos

    //Eliminar datos sobrantes
    $dataRow = array_slice($dataRow, 0, count($dataHeaders));

    return $dataRow;
  }

  public function getEventosAsArray($dataRow, $dataHeaders)
  {

    $search = $this->eventos()
                    ->where('tipo', '!=', 1)
                    ->get();

    foreach($search as $evento){
      $data = $evento->eventoData();
      $inicio = new Carbon($evento->inicio);
      $diffInDays = 1;

      // Encuentra el index de la fecha de inicio
      $dataStart = array_search($inicio->toDateString(), $dataHeaders);
      
      if($dataStart === false){
        continue;
      }

      if($evento->fin){
        $fin = new Carbon($evento->fin);

        if($inicio->equalTo($fin)){
          // Encuentra el index de la fecha de inicio
          $dataEnd = array_search($fin->toDateString(), $dataHeaders);

          if($dataEnd === false){
            continue;
          }

          $diffInDays = $dataEnd - $dataStart;
        }
      }

      // Crea un array temporal usando los index encontrados
      $arrayTempData = array_fill($dataStart, $diffInDays, $data->titulo);
      // Reemplaza los valores del array vacio con los valores del evento
      foreach ($arrayTempData as $key => $data) {
        $dataRow[$key] = $dataRow[$key] == null ? $data : "{$dataRow[$key]}, {$data}";
      }
    }

    return $dataRow;
  }
  
  protected function getFeriadosAsArray($dataRow, $dataHeaders)
  {
    foreach (EmpleadosEvento::feriados() as $feriado){
      // Encuentro el index de la fecha del feriado
      $key = array_search($feriado, $dataHeaders);
      if($key === false){
        continue;
      }
      $dataRow[$key] = 'Feriado';
    }

    return $dataRow;
  }

  public function despidoORenuncia()
  {
    return $this->eventos()->where('tipo', 6)->orWhere('tipo', 7)->count();
  }

  public function countAsisencias($inicio, $fin)
  {
    $totales = [
      'asistencia' => 0,
      'descanso' => 0
    ];

    $exportStart = new Carbon($inicio);
    $exportEnd   = new Carbon($fin);

    foreach ($this->contratos()->where('inicio', '<', $fin)->get() as $contrato){
      
      $contratoStart = new Carbon($contrato->inicio_jornada);
      // Si el contrato no tiene fecha de fin, se proyectan 3 años desde la fecha de inicio
      $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(3);

      // Diferencia en dias desde el inicio hasta el fin del contrato      
      $diffInDays = $contratoStart->diffInDays($contratoEnd);
      $jornada = $contrato->jornada();
      // Intervalos a iterar para generar los bloquees de trabajo + descanso
      $interval = (int) ceil($diffInDays / $jornada->interval);
      $endDifInDays = 1;

      for ($i=0; $i < $interval; $i++){
        $endJornada = $contratoStart->copy()->addDays($jornada->trabajo-1);

        //Si la fecha de inicio de la jornada es mayor a le fecha de fin del reporte,
        //Salir de todo.
        if($exportEnd->diffInDays($contratoStart, false) > 0){
          break;
        }

        if($i == ($interval - 1)){
          $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

          // Si fecha final de la jornada es mayor a la fecha final de contrato
          // se le restan la diferencia en dias a esa jornada
          if($endDifInDays < 0){
            $endJornada = $endJornada->subDays(($endDifInDays * -1));
          }
        }

        $exportStartDifInDays = $exportStart->diffInDays($contratoStart, false);
        $exportEndDifInDays = $exportEnd->diffInDays($contratoStart, false);
        
        //Evaluar diferencia en dias entre el inicio del reporte, y el inicio de la jornada
        if($exportStartDifInDays >= 0 && $exportEndDifInDays <= $jornada->trabajo){
          //Evaluar diferencia en dias entre el fin del reporte y el fin de la jornada
          $exportEndDifInDays = $exportEnd->diffInDays($endJornada, false);
          if($exportEndDifInDays >= 0){
            $totales['asistencia'] += $jornada->trabajo - $exportEndDifInDays;
            break;
          }else{
            $totales['asistencia'] += $jornada->trabajo;
          }
          
        }elseif(($exportStartDifInDays * -1) <= $jornada->trabajo){
          $totales['asistencia'] += $jornada->trabajo - ($exportStartDifInDays* -1);
        }

        // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
        $contratoStart->addDays($jornada->trabajo);

        if($endDifInDays < 0){
          continue;
        }

        $endJornada = $contratoStart->copy()->addDays($jornada->descanso-1);

        if($i == ($interval - 1)){
          $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

          // Si fecha final de la jornada es menor a la fecha final de contrato
          // se le restan la diferencia en dias a sa jornada
          if($endDifInDays < 0){
            $endJornada = $endJornada->subDays(($endDifInDays * -1));
          }
        }

        $exportStartDifInDays = $exportStart->diffInDays($contratoStart, false);
        $exportEndDifInDays = $exportEnd->diffInDays($contratoStart, false);
        
        //Evaluar diferencia en dias entre el inicio del reporte, y el inicio de la jornada
        if($exportStartDifInDays >= 0 && $exportEndDifInDays <= $jornada->descanso){
          //Evaluar diferencia en dias entre el fin del reporte y el fin de la jornada
          $exportEndDifInDays = $exportEnd->diffInDays($endJornada, false);

          if($exportEndDifInDays >= 0){
            $totales['descanso'] += $jornada->descanso - $exportEndDifInDays;
            break;
          }else{
            $totales['descanso'] += $jornada->descanso;
          }
          
        }elseif(($exportStartDifInDays * -1) <= $jornada->descanso){
          $totales['descanso'] += $jornada->descanso - ($exportStartDifInDays* -1);
        }

        // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
        $contratoStart->addDays($jornada->descanso);
      }// For invertal

    }// Foreach contratos

    return $totales;
  }

  public function isWorkDay()
  {
    $today = Carbon::now();
    $contrato = $this->contratos->last();
    $contratoStart = new Carbon($contrato->inicio_jornada);

    // Si el contrato no tiene fecha de fin, se proyectan 3 años desde la fecha de inicio
    $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(3);
    
    // Diferencia en dias desde el inicio hasta el fin del contrato      
    $diffInDays = $contratoStart->diffInDays($contratoEnd);
    $jornada = $contrato->jornada();
    // Intervalos a iterar para generar los bloquees de trabajo + descanso
    $interval = (int) ceil($diffInDays / $jornada->interval);
    $endDifInDays = 1;

    for ($i = 1; $i <= $interval; $i++){
      $endJornada = $contratoStart->copy()->addDays($jornada->trabajo);

      if($i == $interval){
        $endDifInDays = $endJornada->diffInDays($contratoEnd, false);

        // Si fecha final de la jornada es menor a la fecha final de contrato
        // se le restan la diferencia en dias a sa jornada
        if($endDifInDays < 0){
          $endJornada = $endJornada->subDays(($endDifInDays * -1));
        }
      }

      if($today->between($contratoStart, $endJornada)){
        return true;
      }
      // Se aumenta la fecha de inicio con la cantidad de dias en la jornada + los descanso
      $contratoStart->addDays($jornada->interval);
    }// For invertal

    return false;
  }

  public function eventsToday()
  {
    $today = date('Y-m-d');

    return $this->eventos()
                ->where(function($query) use ($today){
                  $query->where('inicio', $today)
                        ->orWhere(function($queryWhere) use ($today){
                        $queryWhere->whereNotNull('fin')
                              ->where([
                                ['inicio', '<=', $today],
                                ['fin', '>=', $today]
                              ]);
                        });
                })
                ->where([
                  ['tipo', '!=', 1],
                  ['pago', false]
                ]);
  }

  /*
  * Buscar todos los eventos de tipo Asistencia en los rangos de fechas dados, donde pago sea True
  */
  public function findEvents($inicio, $fin, $diaPago = true, $comparacion = '=', $tipo = 1){
    return $this->eventos()
                ->where(function($query) use ($inicio, $fin){
                  $query->where('inicio', $inicio)
                        ->when($fin, function($queryWhen) use ($inicio, $fin){
                        $queryWhen->orWhere([
                          ['inicio', '>=', $inicio],
                          ['inicio', '<=', $fin]
                        ]);
                      });
                })
                ->where([
                  ['tipo', $comparacion, $tipo],
                  ['pago', $diaPago]
                ]);
  }

  public function getAsistencias()
  {
    return $this->getEventos(false, '=', 1, 1);
  }

  public function getAlcanceLiquido()
  {
    return $this->contratos()->pluck('sueldo')->last();
  }

  public function getAsistenciasByMonth($month)
  {
    $inicio = Carbon::create(null, $month, 1);
    $fin = $inicio->copy()->endOfMonth();

    $asistencias = $this->findEvents($inicio->toDateString(), $fin->toDateString())->count();

    return $asistencias;
  }

  public function calculateAnticiposByMonth($month)
  {
    $inicio = Carbon::create(null, $month, 1);
    $fin = $inicio->copy()->endOfMonth();

    $anticipos = $this->anticipos()
                        ->where([
                          ['fecha', '>=', $inicio->toDateString()],
                          ['fecha', '<=', $fin->toDateString()]
                        ])
                        ->sum('anticipo');

    return $anticipos;
  }

  public function calculateBonoReemplazoByMonth($month)
  {
    $inicio = Carbon::create(null, $month, 1);
    $fin = $inicio->copy()->endOfMonth();

    $reemplazos = $this->reemplazos()
                        ->where([
                          ['inicio', '>=', $inicio->toDateString()],
                          ['inicio', '<=', $fin->toDateString()]
                        ])->sum('valor');

    return $reemplazos;
  }

  public function calculateSueldoLiquido($alcanceLiquido, $asistencias, $anticipo, $bonoReemplazo)
  {
    $sueldoDiario = $alcanceLiquido / 30;
    $sueldoLiquido = (($sueldoDiario * $asistencias) - $anticipo) + $bonoReemplazo;

    return $sueldoLiquido;
  }

  public function getComidas()
  {
    return $this->eventos()
                ->where([
                  ['tipo', 1],
                  ['comida', true],
                  ['pago', true]
                ])
                ->get();
  }

  public function getComidasToCalendar()
  {
    $comidas = [];

    foreach($this->getComidas() as $comida){
      $comidas[] = [
        'resourceId' => $this->id,
        'id' => 'C'.$comida->id,
        'className' => '',
        'title' => 'Comida',
        'start' => $comida->inicio,
        'end' => null,
        'color' => '#001f3f'
      ];
    }// Foreach Comidas

    return $comidas;
  }

  public function getSueldoLiquido()
  {
    $month = $this->contrato->getPaymentMonth(true);

    $alcanceLiquido = $this->getAlcanceLiquido();
    $asistencias = $this->getAsistenciasByMonth($month);
    $anticipo = $this->calculateAnticiposByMonth($month);
    $bonoReemplazo = $this->calculateBonoReemplazoByMonth($month);
    $sueldoLiquido = $this->calculateSueldoLiquido($alcanceLiquido, $asistencias, $anticipo, $bonoReemplazo);

    return $sueldoLiquido;
  }

}
