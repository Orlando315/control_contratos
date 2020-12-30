<?php

use Illuminate\Database\Seeder;
use App\Models\{Modulo, Permission, Role};

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // para Developers
      $moduloDev = Modulo::firstWhere('name', 'development');
      $roleDev = Role::firstWhere('name', 'developer');
      $permissionGod = Permission::create([
        'modulo_id' => $moduloDev->id,
        'name' => 'god',
        'display_name' => 'Modo dios',
        'description' => 'Administrar todo + Roles y Permissions.',
      ]);
      $roleDev->attachPermission($permissionGod);

      // para Superadmin
      $moduloSuper = Modulo::firstWhere('name', 'superadmin');
      $roleSuper = Role::firstWhere('name', 'superadmin');
      $permissionSuper = Permission::create([
        'modulo_id' => $moduloSuper->id,
        'name' => 'super',
        'display_name' => 'Súperadministrador',
        'description' => 'Administrar todo en el sistema.',
      ]);
      $roleSuper->attachPermission($permissionSuper);

      // para Empresas
      $moduloUsers = Modulo::firstWhere('name', 'users');
      $roleEmpresa = Role::firstWhere('name', 'empresa');
      $roleAdmin = Role::firstWhere('name', 'admin');
      $permissionUserIndex = Permission::create([
        'modulo_id' => $moduloUsers->id,
        'name' => 'user-index',
        'display_name' => 'Listar usuarios',
      ]);
      $permissionUserView = Permission::create([
        'modulo_id' => $moduloUsers->id,
        'name' => 'user-view',
        'display_name' => 'Ver usuarios',
      ]);
      $permissionUserCreate = Permission::create([
        'modulo_id' => $moduloUsers->id,
        'name' => 'user-create',
        'display_name' => 'Crear usuario',
      ]);
      $permissionUserEdit = Permission::create([
        'modulo_id' => $moduloUsers->id,
        'name' => 'user-edit',
        'display_name' => 'Editar usuario',
      ]);
      $permissionUserDelete = Permission::create([
        'modulo_id' => $moduloUsers->id,
        'name' => 'user-delete',
          'display_name' => 'Eliminar usuario',
      ]);

      $roleEmpresa->attachPermissions([
        $permissionUserIndex,
        $permissionUserView,
        $permissionUserCreate,
        $permissionUserEdit,
        $permissionUserDelete,
      ]);

      $roleAdmin->attachPermissions([
        $permissionUserIndex,
        $permissionUserView,
        $permissionUserCreate,
        $permissionUserEdit,
        $permissionUserDelete,
      ]);
    }
}
