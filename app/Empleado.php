<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\EmpresaScope;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Empleado extends Model
{
    use LogEvents;

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
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'fecha_nacimiento' => 'Fecha de nacimiento',
      'nombre_emergencia' => 'Nombre de contacto de emergencia',
      'telefono_emergencia' => 'Teléfono de contacto de emergencia',
      'talla_camisa' => 'Talla de camisa',
      'talla_zapato' => 'Talla de zapato',
      'talla_pantalon' => 'Talla de pantalon',
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
      return $this->hasOne('App\User');
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
     * Obtener los Contratos del Empleado
     */
    public function lastContrato()
    {
      return $this->hasOne('App\EmpleadosContrato')->latest();
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
      $carpetasRequisitos = $this->carpetas()->requisito()->distinct('requisito_id')->get();

      return $this->contrato
                  ->requisitos()
                  ->ofType('empleados')
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
      return $this->contrato->requisitos()->ofType('empleados')->where('folder', $folder)->whereNotIn('id', $ids)->get();
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
        // Si el contrato no tiene fecha de fin, se proyectan 10 años desde la fecha de inicio
        $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(10);
        
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
        }// For interval

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
    public function getDataAsArray($inicio, $fin)
    {
      // Contratos
      $lastContrato  = $this->contratos->last();
      $firstContrato = $this->contratos->first();
      
      $carbonInicioLastContrato = new Carbon($lastContrato->inicio_jornada);
      // Si el lastContrato no tiene fecha de fin, se proyectan 10 años desde la fecha de inicio
      $finLastContrato = $lastContrato->fin ?? $carbonInicioLastContrato->addYears(10);

      // Periodo desde el inicio del 1er contrato, hasta el fin del ultimo
      $inicio = $inicio ?? $firstContrato->inicio_jornada;
      $fin    = $fin ?? $finLastContrato;

      $periodo = new CarbonPeriod($inicio, $fin);

      // Titulo de columna para el excel
      $dataHeaders = ['Fechas'];
      $dataRow = array_fill(0, count($periodo) + 1, null);

      foreach($periodo as $date){
        // Headers para el excel
        $dataHeaders[] = $date->format('Y-m-d');
      }

      $jornadas = $this->proyectarJornadaAsArray($dataRow, $dataHeaders, $inicio, $fin);
      // Titulo de columna para el excel
      $jornadas[0] = 'Jornadas';

      $eventos  = $this->getEventosAsArray($dataRow, $dataHeaders, $inicio, $fin);
      // Titulo de columna para el excel
      $eventos[0] = 'Eventos';

      $feriados = $this->getFeriadosAsArray($dataRow, $dataHeaders);
      // Titulo de columna para el excel
      $feriados[0] = 'Feridos';

      return [$dataHeaders, $jornadas, $eventos, $feriados];
    }

    /**
     * Obtener la informacion de la jornada del Empleado como array
     *
     * @param  array  $dataRow
     * @param  array  $dataHeaders
     * @param  string  $inicio
     * @param  string  $fin
     * @return array
     */
    public function proyectarJornadaAsArray($dataRow, $dataHeaders, $inicio, $fin)
    {
      $contratos = $this->contratos()
        ->where(function ($query) use ($inicio, $fin){
          $query
            ->where([
              ['inicio_jornada', '<=', $inicio],
              ['fin', '>=', $inicio]
            ])
            ->orWhere([
              ['inicio_jornada', '>=', $inicio],
              ['inicio_jornada', '<=', $fin]
            ])
            ->orWhere(function ($query) use ($inicio, $fin) {
              $query
                ->whereNull('fin')
                ->where(function ($query) use ($inicio, $fin) {
                  $query
                    ->where('inicio_jornada', '<=', $inicio)
                    ->orWhere([
                      ['inicio_jornada', '>=', $inicio],
                      ['inicio_jornada', '<=', $inicio]
                    ]);
                });
            });
        })
        ->get();

      $carbonInicio = new Carbon($inicio);

      foreach($contratos as $contrato){
        $contratoStart = new Carbon($contrato->inicio_jornada);
        // Si el contrato no tiene fecha de fin, se proyectan 10 años desde la fecha de inicio
        $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(10);

        // Diferencia en dias desde el inicio hasta el fin del contrato
        $diffInDays = $contratoStart->diffInDays($contratoEnd);
        $jornada = $contrato->jornada();
        // Intervalos a iterar para generar los bloquees de trabajo + descanso
        $interval = (int) ceil($diffInDays / $jornada->interval);
        $endDiffInDays = 1;

        for($i=0; $i < $interval; $i++){
          $endJornada = $contratoStart->copy()->addDays($jornada->trabajo);

          if($i == ($interval - 1)){
            $endDiffInDays = $endJornada->diffInDays($contratoEnd, false);
          }

          // Verificar la fecha de inicio solicitada, contra la fecha de inicio de la jornada de descanso
          $isStartDateLower = $contratoStart->lt($carbonInicio);
          // Evaluar la diferencia de dias entre la fecha actual del ciclo y la fecha de inicio solicitada
          $contratoStartDiffWithRequestStart = $isStartDateLower ? ($jornada->trabajo - ($contratoStart->diffInDays($carbonInicio))) : $jornada->trabajo;
          // Fecha a buscar en el array
          $startDateToSearch = $contratoStart->toDateString();

          // Si la fecha actual es menor a la fecha solicitada
          // Y la diferencia entre ambas es mayor a cero pero menor a la cantidad de didas de la jornada de descanso
          // se reemplaza la fecha de hoy con la de inicio solicitada para evitar que sea saltada y no se coloque ningun dia de esa jornada
          if($isStartDateLower && ($contratoStartDiffWithRequestStart > 0 && $contratoStartDiffWithRequestStart < $jornada->trabajo)){
            $startDateToSearch = $carbonInicio->toDateString();
          }
          // Encuentro el index de la fecha de inicio de la jornada
          $dataStart = array_search($startDateToSearch, $dataHeaders);
          if($dataStart !== false){
            $fillUntilKey = $isStartDateLower ? ($jornada->trabajo - ($contratoStart->diffInDays($carbonInicio))) : $jornada->trabajo;
            
            // Crea un array temporal desde el index encontrado y la cantidad de dias de trabajo
            $arrayTempData = array_fill($dataStart, $fillUntilKey, 'Trabajo ' . $contrato->jornada);
            // Reemplaza los valores del array vacio con los valores de la jornada
            $dataRow = array_replace($dataRow, $arrayTempData);
          }

          // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
          $contratoStart->addDays($jornada->trabajo);
          
          if($endDiffInDays < 0){
            continue;
          }

          // Verificar la fecha de inicio solicitada, contra la fecha de inicio de la jornada de descanso
          $isStartDateLower = $contratoStart->lt($carbonInicio);
          // Evaluar la diferencia de dias entre la fecha actual del ciclo y la fecha de inicio solicitada
          $contratoStartDiffWithRequestStart = $isStartDateLower ? ($jornada->descanso - ($contratoStart->diffInDays($carbonInicio))) : $jornada->descanso;
          // Fecha a buscar en el array
          $startDateToSearch = $contratoStart->toDateString();

          // Si la fecha actual es menor a la fecha solicitada
          // Y la diferencia entre ambas es mayor a cero pero menor a la cantidad de didas de la jornada de descanso
          // se reemplaza la fecha de hoy con la de inicio solicitada para evitar que sea saltada y no se coloque ningun dia de esa jornada
          if($isStartDateLower && ($contratoStartDiffWithRequestStart > 0 && $contratoStartDiffWithRequestStart < $jornada->descanso)){
            $startDateToSearch = $carbonInicio->toDateString();
          }

          // Encuentro el index de la fecha de inicio de la jornada
          $dataStart = array_search($startDateToSearch, $dataHeaders);
          if($dataStart !== false){
            // Crea un array temporal desde el index encontrado y la cantidad de dias de descanso
            $arrayTempData = array_fill($dataStart, $jornada->descanso, 'Descanso ' . $contrato->jornada);
            // Reemplaza los valores del array vacio con los valores de la jornada
            $dataRow = array_replace($dataRow, $arrayTempData);
          }
          // Se aumenta la fecha de inicio con la cantidad de dias en la jornada
          $contratoStart->addDays($jornada->descanso);
        }// For interval
      }// Foreach contratos

      //Eliminar datos sobrantes
      $dataRow = array_slice($dataRow, 0, count($dataHeaders));

      return $dataRow;
    }

    /**
     * Obtener la informacion de los Eventos del Empleado como array
     *
     * @param  array  $dataRow
     * @param  array  $dataHeaders
     * @param  string  $inicio
     * @param  string  $fin
     * @return array
     */
    public function getEventosAsArray($dataRow, $dataHeaders, $inicio, $fin)
    {
      $search = $this->eventos()
        ->where('tipo', '!=', 1)
        ->where(function ($query) use ($inicio, $fin){
          $query->where(function ($query) use ($inicio, $fin){
            $query
              ->whereNull('fin')
              ->where([
                ['inicio', '<=', $inicio],
                ['fin', '>=', $inicio]
              ]);
          })
          ->orWhere(function ($query) use ($inicio, $fin) {
            $query
              ->whereNotNull('fin')
              ->where([
                ['inicio', '>=', $inicio],
                ['inicio', '<=', $fin]
              ]);
          });
        })
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
        // Si el contrato no tiene fecha de fin, se proyectan 10 años desde la fecha de inicio
        $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(10);

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
        }// For interval

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

      // Si el contrato no tiene fecha de fin, se proyectan 10 años desde la fecha de inicio
      $contratoEnd = $contrato->fin ?? $contratoStart->copy()->addYears(10);
      
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
      }// For interval

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

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults();
    }
}
