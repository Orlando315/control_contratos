<?php

namespace App\Policies\Admin;

use App\Inventario;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioPolicy
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
      return $user->hasPermission('inventario-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Inventario  $inventario
     * @return mixed
     */
    public function view(User $user, Inventario $inventario)
    {
      return $user->hasPermission('inventario-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Inventario  $inventario
     * @return mixed
     */
    public function update(User $user, Inventario $inventario)
    {
      return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Inventario  $inventario
     * @return mixed
     */
    public function delete(User $user, Inventario $inventario)
    {
      return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Inventario  $inventario
     * @return mixed
     */
    public function restore(User $user, Inventario $inventario)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Inventario  $inventario
     * @return mixed
     */
    public function forceDelete(User $user, Inventario $inventario)
    {
        //
    }
}
