<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmpleadosEvento extends Model
{
  protected $fillable = [
    'empleado_id',
    'inicio',
    'fin',
    'tipo'
  ];

  public function getFinAttribute($date)
  {
    if($date){
      $date = new Carbon($date);
      return $date->addDays(1)->toDateString();
    }
  }

  public function eventoData()
  {
    switch($this->tipo){
      case '1':
        $data = ['titulo'=>'Licencia médica', 'color'=>'#aa6708'];
        break;
      case '2':
        $data = ['titulo'=>'Vacaciones', 'color'=> '#6f5499'];
        break;
      case '3':
        $data = ['titulo'=>'Permiso', 'color'=> '#3c8dbc'];
        break;
      case '4':
        $data = ['titulo'=>'Permiso no remunerable', 'color'=> '#222d32'];
        break;
      case '5':
        $data = ['titulo'=>'Despido', 'color'=> '#ce4844'];
        break;
      case '6':
        $data = ['titulo'=>'Renuncia', 'color'=> '#ce4844'];
        break;
      case '7':
        $data = ['titulo'=>'Inasistencia', 'color'=> '#4f5b94'];
        break;
    }
    return (object) $data;
  }

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

  public static function exportAll($inicio, $fin)
  {
    $inicioCarbon = new Carbon($inicio);
    $finCarbon    = new Carbon($fin);

    //$periodo = new CarbonPeriod($inicioCarbon, $finCarbon);

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

          $dataRow[($evento->tipo + 2)] += $diff;
        }else{
          $dataRow[($evento->tipo + 2)]++;
        }
        
      }

      $asistencias = $empleado->countAsisencias($inicio, $fin);

      $dataRow[2] = $asistencias['descanso'];
      $dataRow[1] = ($asistencias['asistencia']- $dataRow[9] ) < 0 ? 0 : ($asistencias['asistencia'] - $dataRow[9]);

      $allData = array_merge($allData, [$dataRow]);
    }
    return $allData;
  }
}
