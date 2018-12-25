<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      
      App\Empresa::create([
        'nombres' => 'Empresa',
        'representante' => 'Representante',
      ]);

      App\ConfiguracionEmpresa::create([
        'empresa_id' => 1,
        'jornada' => '5x2'
      ]);

      App\User::create([
        'empresa_id' => 1,
        'empleado_id' => null,
        'tipo' =>  1,
        'nombres' => 'Empresa',
        'apellidos' => null,
        'rut' => '1111111-1',
        'email' => 'empresa@test.com',
        'telefono' => '0000000000',
        'usuario' => '1111111-1',
        'password' => bcrypt('1111111-1')
      ]);

      App\User::create([
        'empresa_id' => 1,
        'empleado_id' => null,
        'tipo' =>  2,
        'nombres' => 'Pedro',
        'apellidos' => 'Garcia',
        'rut' => '111111-2',
        'email' => 'user@test.com',
        'telefono' => '0000000001',
        'usuario' => '111111-2',
        'password' => bcrypt('111111-2')
      ]);

      App\Contrato::create([
        'empresa_id' => 1,
        'nombre' => 'Contrato #1',
        'inicio' => '2018-12-01',
        'fin' => '2019-01-30',
        'valor' => 40000000
      ]);

      App\Empleado::create([
        'empresa_id' => 1,
        'contrato_id' => 1,
        'sexo' => 'M',
        'fecha_nacimiento' => '2010-05-27',
        'direccion' => 'Dirección',
        'talla_zapato' => '42',
        'talla_pantalon' => '32',
      ]);

      App\EmpleadosBanco::create([
        'empleado_id' => 1,
        'nombre' => 'Banco',
        'tipo_cuenta' => 'Ahorro',
        'cuenta' => '001024457850434440'
      ]);

      App\EmpleadosContrato::create([
        'empleado_id' => 1,
        'sueldo' => 15000,
        'inicio' => '2018-11-20',
        'fin' => '2019-01-15',
        'jornada' => '5x2',
        'inicio_jornada' => '2018-12-01'
      ]);

      App\User::create([
        'empresa_id' => 1,
        'empleado_id' => 1,
        'tipo' =>  4,
        'nombres' => 'Nombres',
        'apellidos' => 'Apellidos',
        'rut' => '111111-3',
        'email' => 'empleado@test.com',
        'telefono' => '0000000003',
        'usuario' => '111111-3',
        'password' => bcrypt('111111-3')
      ]);
    }
}
