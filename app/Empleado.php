<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\EmpresaScope;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Empleado extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'sexo',
      'fecha_nacimiento',
      'direccion',
      'profesion',
      'nombre_emergencia',
      'telefono_emergencia',
      'talla_camisa',
      'talla_zapato',
      'talla_pantalon',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['usuario'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['empresa_id'];

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
     * Establecer la fecha de nacimiento
     *
     * @param  string  $value
     * @return void
     */
    public function setFechaNacimientoAttribute($value)
    {
      $this->attributes['fecha_nacimiento'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    /**
     * Obtener el atributo formateado
     *
     * @param  string  $value
     * @return string
     */
    public function getFechaNacimientoAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Obtener el Usuario al que pertenece
     */
    public function usuario()
    {
      return $this->hasOne('App\Usuario');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener los Contratos del Empleado
     */
    public function contratos()
    {
      return $this->hasMany('App\EmpleadosContrato');
    }

    /**
     * Obtener la informacion Bancaria del Empleado
     */
    public function banco()
    {
      return $this->hasOne('App\EmpleadosBanco');
    }

    /**
     * Obtener los Documentos (Adjuntos) del Empleado
     */
    public function documentos()
    {
      return $this->morphMany('App\Documento', 'documentable');
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener los Eventos
     */
    public function eventos()
    {
      return $this->hasMany('App\EmpleadosEvento');
    }

    /**
     * Obtener los Anticipos
     */
    public function anticipos()
    {
      return $this->hasMany('App\Anticipo');
    }

    /**
     * Obtener el ultimo Anticipo
     */
    public function latestAnticipo()
    {
      return $this->hasOne('App\Anticipo')->aprobados()->select(['empleado_id', 'anticipo'])->latest();
    }

    /**
     * Obtener los Reemplazos del User
     */
    public function reemplazos()
    {
      return $this->hasMany('App\EmpleadosEvento', 'reemplazo')->where('tipo', 9);
    }

    /**
     * Obtener los Sueldos
     */
    public function sueldos()
    {
      return $this->hasMany('App\EmpleadosSueldo');
    }

    /**
     * Obtener las Entregas
     */
    public function entregas()
    {
      return $this->usuario()->first()->hasMany('App\InventarioEntrega', 'entregado')->with('Inventario:id,nombre');
    }

    /**
     * Obtener las Carpetas
     */
    public function carpetas()
    {
      return $this->morphMany('App\Carpeta', 'carpetable');
    }

    /**
     * Obtener las PlantillaDocumento (Documetos) en el Contrato
     */
    public function plantillaDocumentos()
    {
      return $this->hasMany('App\PlantillaDocumento');
    }

    /**
     * Obtener las Solicitudes
     */
    public function solicitudes()
    {
      return $this->hasMany('App\Solicitud');
    }

    /**
     * Obtener otros Empleados en el mismo Contrato
     */
    public function otrosEmpleados()
    {
      return $this->contrato
                  ->empleados()
                  ->select('id')
                  ->where('id', '!=', $this->id)
                  ->with('usuario:empleado_id,nombres,apellidos,rut');
    }

    /**
     * Obtener los Requisitos en el Contrato
     */
    public function requisitos()
    {
      $documentosRequisitos = $this->documentos()->requisito()->distinct('requisito_id')->get();

      return $this->contrato
                  ->requisitos()
                  ->ofType('empleados')
                  ->get()
                  ->map(function ($requisito) use ($documentosRequisitos) {
                    $requisito->documento = $documentosRequisitos->firstWhere('requisito_id', $requisito->id);
                    return $requisito;
                  });
    }

    /**
     * Obtener los Requisitos que aun no tienen un Documento agregado
     */
    public function requisitosFaltantes()
    {
      $ids = $this->documentos()->requisito()->distinct('requisito_id')->pluck('requisito_id');
      return $this->contrato->requisitos()->ofType('empleados')->whereNotIn('id', $ids)->get();
    }

    /**
     * Obtener el directorio donde se guardaran Documentos y Carpetas
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->empresa_id.'/Empleado'.$this->id;
    }

    /**
     * Obtener la informacion de la Jornada del Empleado para ser mostrada en el calendario
     */
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

    /**
     * Obtener los Eventos del Empleado para el calendario
     *
     * @param  bool  $clickable
     * @param  string  $comparacion  El tipo de comparacion usaba en el metodo where para la consulta de los Eventos
     * @param  int  $tipo  El tipo de Evento a buscar
     * @param  bool  $pago  Si el Evento es pago o no
     * @param  bool  $status  Si el Evento esta aprobado o no
     * @return array  $eventos
     */
    public function getEventos($clickable = true, $comparacion = '!=', $tipo = 1, $pago = null, $status = true)
    {
      $eventos = [];
      $search = $this->eventos()
                      ->where(function ($query) use ($comparacion, $tipo, $pago){
                        $query->where('tipo', $comparacion, $tipo)
                              ->when($pago, function($queryWhen) use ($pago){
                              $queryWhen->where('pago', $pago);
                            });
                      })
                      ->where('status', $status)
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

    /**
     * Obtener los dias que son feriados para mostrar en el Calendario
     */
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

    /**
     * Obtener toda la informacion de Jornada, Eventos y dias feriados para exportar a Excel
     *
     * @param  string  $inicio
     * @param  string  $fin
     * @return array
     */
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

    /**
     * Obtener la informacion de la jornada del Empleado como array
     *
     * @param  array $dataRow
     * @param  array $dataHeaders
     * @return array
     */
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

    /**
     * Obtener la informacion de los Eventos del Empleado como array
     *
     * @param  array $dataRow
     * @param  array $dataHeaders
     * @return array
     */
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
    
    /**
     * Obtener la informacion de los dias feriados como array
     *
     * @param  array $dataRow
     * @param  array $dataHeaders
     * @return array
     */
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

    /**
     * Evaluar si el Empleado tiene Eventos de despido o renuncia
     *
     * @return int
     */
    public function despidoORenuncia()
    {
      return $this->eventos()->where('tipo', 6)->orWhere('tipo', 7)->count();
    }

    /**
     * Obtener la cuenta de las asistencias e inasistencias del Empleado en las fechas especificadas
     *
     * @param  string $inicio
     * @param  string $fin
     * @return array
     */
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

    /**
     * Evaluar si el dia "actual" es un dia que el Empleado deba trabajar
     *
     * @return bool
     */
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

    /**
     * Obtener la informacion de los Eventos del dia "actual"
     */
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

    /**
     * Obtener la informacion de los Eventos tipo asistencias del Empleado
     */
    public function getAsistencias()
    {
      return $this->getEventos(false, '=', 1, 1);
    }

    /**
     * Obtener la informacion del "alcance liquido"
     */
    public function getAlcanceLiquido()
    {
      return $this->contratos()->pluck('sueldo')->last();
    }

    /**
     * Obtener la informacion de los Eventos tipo asistencias del Empleado
     *
     * @param  string  $inicio
     */
    public function getAsistenciasByMonth($inicio)
    {
      $fin = $inicio->copy()->endOfMonth();

      $asistencias = $this->findEvents($inicio->toDateString(), $fin->toDateString())->count();

      return $asistencias;
    }

    /**
     * Obtener el total de los Anticipos del Empleado por el mes especificado
     *
     * @param  string  $inicio
     * @return integer
     */
    public function calculateAnticiposByMonth($inicio)
    {
      $fin = $inicio->copy()->endOfMonth();

      $anticipos = $this->anticipos()
                        ->aprobados()
                        ->where([
                          ['fecha', '>=', $inicio->toDateString()],
                          ['fecha', '<=', $fin->toDateString()]
                        ])
                        ->sum('anticipo');

      return $anticipos;
    }

    /**
     * Obtener el total de los bonos por Eventos tipo Reemplazo en el mes especificado
     *
     * @param  string  $inicio
     * @return int
     */
    public function calculateBonoReemplazoByMonth($inicio)
    {
      $fin = $inicio->copy()->endOfMonth();

      $reemplazos = $this->reemplazos()
                          ->where([
                            ['inicio', '>=', $inicio->toDateString()],
                            ['inicio', '<=', $fin->toDateString()]
                          ])->sum('valor');

      return $reemplazos;
    }

    /**
     * Calcular el total del "sueldo liquido" del Empleado
     *
     * @param  int  $alcanceLiquido
     * @param  int  $asistencias
     * @param  int  $anticipo
     * @param  int  $bonoReeplazo
     * @return int
     */
    public function calculateSueldoLiquido($alcanceLiquido, $asistencias, $anticipo, $bonoReemplazo)
    {
      $sueldoDiario = $alcanceLiquido / 30;
      $sueldoLiquido = (($sueldoDiario * $asistencias) - $anticipo) + $bonoReemplazo;

      return $sueldoLiquido;
    }

    /**
     * Obtener la informacion de los Eventos tipo comidas del Empleado
     */
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

    /**
     * Obtener la informacion de los Eventos tipo comidas del Empleado, como un Array
     *
     * @return array
     */
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

    /**
     * Obtener la informacion del "sueldo liquido" del Empleado
     *
     * @return int
     */
    public function getSueldoLiquido()
    {
      $month = $this->contrato->getPaymentMonth(true);

      if(!$month){
        return 0;
      }

      $alcanceLiquido = $this->getAlcanceLiquido();
      $asistencias = $this->getAsistenciasByMonth($month);
      $anticipo = $this->calculateAnticiposByMonth($month);
      $bonoReemplazo = $this->calculateBonoReemplazoByMonth($month);
      $sueldoLiquido = $this->calculateSueldoLiquido($alcanceLiquido, $asistencias, $anticipo, $bonoReemplazo);

      return $sueldoLiquido;
    }

    /**
     * Obtener el nombre completo del Empleado
     */
    public function nombre()
    {
      return $this->usuario->nombres.' '.$this->usuario->apellidos;
    }
}
