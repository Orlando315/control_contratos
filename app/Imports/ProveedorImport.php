<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use App\Proveedor;

class ProveedorImport implements OnEachRow, WithHeadingRow, WithMultipleSheets
{
    /**
     * Campos obligatorios
     * 
     * @var array
     */
    private $required = [
      'nombre',
      'telefono',
      'rut',
      'dv',
    ];

    /**
     * Tipo de Proveedor a almacenar
     * 
     * @var string
     */
    private $type;

    /**
     * 
     * 
     * @param  string  $type
     */
    public function __construct($type)
    {
      $this->type = $type;
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
      // o ya se encuentra registrado
      // no se inserta
      if($item->filter()->isEmpty() || !$this->hasRequiredValues($item) || $this->isRegistered($item)){
        return false;
      }

      $this->{$this->type}($item);
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
      $exists = Proveedor::where('rut', $rut)
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
      return $this->type == 'persona' ? 5 : 6;
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
     * Procedimiento para almacenar los Proveedores tipo Persona
     * 
     * @param  \Illuminate\Support\Collection  $item
     * @return void
     */
    private function persona(Collection $item)
    {
      $rut = $item['rut'].'-'.$item['dv'];
      $proveedor = new Proveedor($item->toArray());
      $proveedor->empresa_id = Auth::user()->empresa->id;
      $proveedor->type = 'persona';
      $proveedor->rut = $rut;
      $proveedor->save();

      if($item['ciudad'] || $item['comuna'] || $item['direccion']){
        $direccion = [
          'ciudad' => $item['ciudad'],
          'comuna' => $item['comuna'],
          'direccion' => $item['direccion'],
          'status' => true,
        ];

        $proveedor->direcciones()->create($direccion);
      }
    }

    /**
     * Procedimiento para almacenar los Proveedores tipo Empresa
     * 
     * @param  \Illuminate\Support\Collection  $item
     * @return void
     */
    private function empresa(Collection $item)
    {
      try{
        $data = sii()->busquedaReceptor($item['rut'], $item['dv']);
      }catch(\Exception $e){
        return false;
      }

      $rut = $item['rut'].'-'.$item['dv'];      
      $proveedor = new Proveedor([
        'type' => 'empresa',
        'rut' => $rut,
        'nombre' => $data['razon_social'],
      ]);
      $proveedor->empresa_id = Auth::user()->empresa->id;
      $proveedor->save();

      // Contacto
      $proveedor->contactos()->create([
        'nombre' => $item['nombre'],
        'telefono' => $item['telefono'],
        'email' => $item['email'],
        'cargo' => $item['cargo'],
        'descripcion' => $item['descripcion'],
      ]);

      // Direcciones
      $direcciones = [];
      $direcciones[] = [
        'ciudad' => $data['ciudad_seleccionada'],
        'comuna' => $data['comuna_seleccionada'],
        'direccion' => $data['direccion_seleccionada'],
        'status' => true,
      ];

      if(isset($data['direcciones']) && count($data['direcciones']) > 0){
        foreach ($data['direcciones'] as $direccion){
          $value = array_values($direccion)[0];

          if($value == $data['direccion_seleccionada']){
            continue;
          }

          $direcciones[] = [
            'direccion' => $value,
          ];
        }
      }

      $proveedor->direcciones()->createMany($direcciones);
    }
}
