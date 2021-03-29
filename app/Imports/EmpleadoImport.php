<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use App\{Empleado, Contrato, User, EmpleadosContrato, Role};
use DateTime;

class EmpleadoImport implements OnEachRow, WithHeadingRow, WithMultipleSheets
{
    /**
     * [$contrato description]
     * 
     * @var null
     */
    private $contrato = null;

    /**
     * Campos obligatorios
     * 
     * @var array
     */
    private $required = [
      'nombres',
      'apellidos',
      'rut',
      'dv',
      'fecha_de_nacimiento',
      'direccion',
      'sexo',
      'nombre_del_banco',
      'tipo_de_cuenta',
      'n0_de_cuenta',
      'inicio_fecha',
      'inicio_de_jornada_fecha',
      'sueldo',
    ];

    /**
     * @param  \App\Contrato  $contrato
     * @return void
     */
    public function __construct(Contrato $contrato)
    {
      $this->contrato = $contrato;
    }

    /**
     * Registrar un Empleado con todos los datos necesarios
     * 
     * @param  \Maatwebsite\Excel\Row  $row
     * @return void
     */
    public function onRow(Row $row)
    {
      $item = $row->toCollection();
      // Si la columna esta completamente vacia
      // o no cumple con los campos necesarios
      // o contiene fechas invalidas
      // o ya se encuentra registrado
      // no se inserta
      if($item->filter()->isEmpty() || !$this->hasRequiredValues($item) || $this->hasInvalidDates($item) || $this->isRegistered($item)){
        return false;
      }

      $empresa = Auth::user()->empresa;

      $rut = $item['rut'].'-'.$item['dv'];
      $fechaNacimiento = $this->convertDate($item['fecha_de_nacimiento']);
      $empleado = new Empleado([
        'sexo' => $item['sexo'],
        'fecha_nacimiento' => $fechaNacimiento,
        'direccion' =>  $item['direccion'],
        'talla_camisa' => $item['talla_de_camisa'],
        'talla_pantalon' => $item['talla_de_pantalon'],
        'talla_zapato' => $item['talla_de_zapato'],
        'profesion' => $item['profesion'],
        'nombre_emergencia' => $item['nombre'],
        'telefono_emergencia' => $item['telefono_de_contacto'],
      ]);
      $empleado->empresa_id = $empresa->id;
      $this->contrato->empleados()->save($empleado);

      // Crear Usuario
      $usuario = new User([
        'nombres' => $item['nombres'],
        'apellidos' => $item['apellidos'],
        'rut' =>  $rut,
        'telefono' => $item['telefono'],
        'email' => $item['email'],
      ]);
      $usuario->password = bcrypt($rut);
      $usuario->usuario  = $rut;
      $empleado->usuario()->save($usuario);
      $empresa->users()->attach($usuario->id);

      // Asignar role al usuario
      $role = Role::firstWhere('name', 'empleado');
      $usuario->attachRole($role);

      $jornada = (isset($item['jornada']) && in_array($item['jornada'], EmpleadosContrato::getJornadas()))
        ? $item['jornada']
        : Auth::user()->empresa->configuracion->jornada;
      
      $inicioContrato = $this->convertDate($item['inicio_fecha']);
      $inicioJornada = $this->convertDate($item['inicio_de_jornada_fecha']);
      // Si la fecha de inicio de la Jornada es menor a la fecha de inicio del Contrato,
      // se coloca la misma fecha de inicio del Contrato
      $inicioJornada = ($inicioJornada < $inicioContrato) ? $inicioContrato : $inicioJornada;

      $finContrato = is_null($item['fin_fecha']) ? null : $this->convertDate($item['fin_fecha']);
      // Si laa fecha de fin del Contrato es menor a la fecha de Iinicio del Contrato,
      // se coloca como indefinida
      $finContrato = (is_null($finContrato) || ($finContrato < $inicioContrato)) ? null : $finContrato;

      // Crear EmpleadosContrato
      $empleado->contratos()
        ->create([
          'inicio' => $inicioContrato,
          'inicio_jornada' => $inicioJornada,
          'fin' => $finContrato != false ? $finContrato : null,
          'sueldo' => $item['sueldo'],
          'jornada' => $jornada,
          'descripcion' => $item['descripcion'],
        ]);

      // Crear EmpleadosBanco
      $empleado
        ->banco()
        ->create([
          'nombre' => $item['nombre_del_banco'],
          'tipo_cuenta' => $item['tipo_de_cuenta'],
          'cuenta' => $item['n0_de_cuenta'],
        ]);
    }

    /**
     * Evaluar si el item proporcionado tiene todos los valores necesarios
     * 
     * @param  \Illuminate\Support\Collection  $item
     * @return bool
     */
    private function hasRequiredValues(Collection $item): bool
    {
      return !in_array(null, $item->only($this->required)->toArray());
    }

    /**
     * Evaluar si ya hay un Usuario registrado con el rut o email proporcionado
     * 
     * @param  \Illuminate\Support\Collection  $item
     * @return bool
     */
    private function isRegistered(Collection $item): bool
    {
      $rut = $item['rut'].'-'.$item['dv'];
      $exists = User::where('rut', $rut)
        ->when($item['email'], function ($query, $email) {
          return $query->orWhere('email', $email);
        })
        ->exists();

      return $exists;
    }

    /**
     * Especificar el numero de la columna que sera usada como heading
     * 
     * @return int
     */
    public function headingRow(): int
    {
      return 6;
    }

    /**
     * Especificar que hojas del excel se deben usar
     * 
     * @return array
     */
    public function sheets(): array
    {
      return [
        0 => $this,
      ];
    }

    /**
     * Convertir la fecha proporcionado, a una fecha valida (si no lo es)
     * 
     * @param  string|number  $date
     * @return mixed
     */
    private function convertDate($date)
    {
      if(is_null($date)){
        return false;
      }

      if(is_string($date)){
        if($this->isValidDate($date)){
          return $date;
        }

        return false;
      }

      return gmdate('d-m-Y', ($date - 25569) * 86400);
    }

    /**
     * Evaluar si la fecha proporcionada es valida
     * 
     * @param  string  $date
     * @return bool
     */
    private function isValidDate($date): bool
    {
      $d = DateTime::createFromFormat('d-m-Y', $date);
      // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
      return $d && $d->format('d-m-Y') === $date;
    }

    /**
     * Evaluar si alguna de las fechas necesarias es invalida
     * 
     * @param  \Illuminate\Support\Collection  $item
     * @return bool
     */
    private function hasInvalidDates($item): bool
    {
      $fechaNacimiento = $this->convertDate($item['fecha_de_nacimiento']);
      $inicioContrato = $this->convertDate($item['inicio_fecha']);
      $inicioJornada = $this->convertDate($item['inicio_de_jornada_fecha']);

      return ($fechaNacimiento === false || $inicioContrato === false || $inicioJornada === false);
    }
}
