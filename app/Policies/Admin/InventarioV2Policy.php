<?php

namespace App\Policies\Admin;

use App\InventarioV2;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioV2Policy
{
    use HandlesAuthorization;

    /**
     * Verificar una accion antes de validar la peticion por el metodo solicitado.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function before($user, $ability)
    {
      if($user->hasRole('developer|superadmin')){
        return true;
      }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
      return $user->hasPermission('inventario-v2-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2  $inventario
     * @return mixed
     */
    public function view(User $user, InventarioV2 $inventario)
    {
      return $user->hasPermission('inventario-v2-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('inventario-v2-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2  $inventario
     * @return mixed
     */
    public function update(User $user, InventarioV2 $inventario)
    {
      return $user->hasPermission('inventario-v2-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2  $inventario
     * @return mixed
     */
    public function delete(User $user, InventarioV2 $inventario)
    {
      return $user->hasPermission('inventario-v2-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2  $inventario
     * @return mixed
     */
    public function restore(User $user, InventarioV2 $inventario)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2  $inventario
     * @return mixed
     */
    public function forceDelete(User $user, InventarioV2 $inventario)
    {
        //
    }
}
