<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Role::create([
        'name' => 'developer',
        'display_name' => 'Developer',
        'description' => 'Superadmin con acceso a Roles y Permission.',
      ]);

      Role::create([
        'name' => 'superadmin',
        'display_name' => 'Súperadministrador',
        'description' => 'Súperadministrador del sistema. Controla todo.',
      ]);

      Role::create([
        'name' => 'empresa',
        'display_name' => 'Empresa',
        'description' => 'Administrador de la Empresa, este Role no se puede asignar a otros.',
      ]);

      Role::create([
        'name' => 'admin',
        'display_name' => 'Administrador',
        'description' => 'Administrador dentro de una Empresa.',
      ]);

      Role::create([
        'name' => 'vendedor',
        'display_name' => 'Vendedor',
        'description' => 'Vendedor',
      ]);
    }
}
