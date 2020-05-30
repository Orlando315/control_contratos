<?php

use Illuminate\Database\Seeder;
use App\{Empresa, PlantillaVariable};

class PlantillasVariablesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $empresa = Empresa::first();

      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Nombre completo',
        'tipo' => 'text',
        'variable' => '{{nombre_completo}}',
      ]);

      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'RUT',
        'tipo' => 'rut',
        'variable' => '{{rut}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Pasaporte',
        'tipo' => 'text',
        'variable' => '{{pasaporte}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Fecha de nacimiento',
        'tipo' => 'date',
        'variable' => '{{fecha_de_nacimiento}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Nacionalidad',
        'tipo' => 'text',
        'variable' => '{{Nacionalidad}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Dirección',
        'tipo' => 'text',
        'variable' => '{{direccion}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Estado civil',
        'tipo' => 'text',
        'variable' => '{{estado_civil}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Cargo',
        'tipo' => 'text',
        'variable' => '{{cargo}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Fecha de ingreso',
        'tipo' => 'date',
        'variable' => '{{fecha_de_ingreso}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Teléfono',
        'tipo' => 'tel',
        'variable' => '{{telefono}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Correo',
        'tipo' => 'email',
        'variable' => '{{correo}}',
      ]);
      
      App\PlantillaVariable::create([
        'empresa_id' => $empresa->id,
        'nombre' => 'Firma',
        'tipo' => 'firma',
        'variable' => '{{firma}}',
      ]);
    }
}
