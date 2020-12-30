<?php

use Illuminate\Database\Seeder;
use App\Models\Modulo;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Modulo::create([
        'name' => 'development',
        'description' => 'Área de desarrollador',
        'display_name' => 'Área de desarrollador',
      ]);

      Modulo::create([
        'name' => 'superadmin',
        'description' => 'Modulos del superadmin',
        'display_name' => 'Modulos del superadmin',
      ]);

      Modulo::create([
        'name' => 'users',
        'description' => 'Usuarios',
        'display_name' => 'Usuarios',
      ]);
    }
}
