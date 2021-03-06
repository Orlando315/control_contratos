<?php

namespace App\Policies\Admin;

use App\InventarioV2Ingreso;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioV2IngresoPolicy
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
      return $user->hasPermission('inventario-ingreso-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return mixed
     */
    public function view(User $user, InventarioV2Ingreso $ingreso)
    {
      return $user->hasPermission('inventario-ingreso-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('inventario-ingreso-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return mixed
     */
    public function update(User $user, InventarioV2Ingreso $ingreso)
    {
      return $user->hasPermission('inventario-ingreso-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return mixed
     */
    public function delete(User $user, InventarioV2Ingreso $ingreso)
    {
      return $user->hasPermission('inventario-ingreso-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return mixed
     */
    public function restore(User $user, InventarioV2Ingreso $ingreso)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Ingreso  $ingreso
     * @return mixed
     */
    public function forceDelete(User $user, InventarioV2Ingreso $ingreso)
    {
        //
    }
}
