<?php

namespace App\Policies\Admin;

use App\Unidad;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnidadPolicy
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
      return $user->hasPermission('inventario-unidad-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Unidad  $unidad
     * @return mixed
     */
    public function view(User $user, Unidad $unidad)
    {
      return $user->hasPermission('inventario-unidad-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('inventario-unidad-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Unidad  $unidad
     * @return mixed
     */
    public function update(User $user, Unidad $unidad)
    {
      return $user->hasPermission('inventario-unidad-edit') && $unidad->isNotGlobal();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Unidad  $unidad
     * @return mixed
     */
    public function delete(User $user, Unidad $unidad)
    {
      return $user->hasPermission('inventario-unidad-delete') && $unidad->isNotGlobal();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Unidad  $unidad
     * @return mixed
     */
    public function restore(User $user, Unidad $unidad)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Unidad  $unidad
     * @return mixed
     */
    public function forceDelete(User $user, Unidad $unidad)
    {
        //
    }
}
