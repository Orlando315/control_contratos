<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Empleado;
use Illuminate\Support\Facades\Auth;

class Asistencias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asistencias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar eventos de tipo Asistencia a todos lo empleados diariamente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $empleados = Empleado::all();
      $today = date('Y-m-d');

      foreach($empleados as $empleado){
        if($empleado->isWorkDay()){
          $eventosExists = $empleado->eventsToday()->exists();
          
          $empleado->eventos()->firstOrCreate([
            'inicio' => $today,
            'tipo' =>  1,
            'jornada' => $empleado->contratos->last()->jornada
          ],[
            'comida' => !$eventosExists,
            'pago' => !$eventosExists
          ]);
        }
      }
    }
}
