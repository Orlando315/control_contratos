<?php

namespace App\Policies\Admin;

use App\Plantilla;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlantillaPolicy
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
      return $user->hasPermission('plantilla-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Plantilla  $plantilla
     * @return mixed
     */
    public function view(User $user, Plantilla $plantilla)
    {
      return $user->hasPermission('plantilla-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('plantilla-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Plantilla  $plantilla
     * @return mixed
     */
    public function update(User $user, Plantilla $plantilla)
    {
      return $user->hasPermission('plantilla-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Plantilla  $plantilla
     * @return mixed
     */
    public function delete(User $user, Plantilla $plantilla)
    {
      return $user->hasPermission('plantilla-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Plantilla  $plantilla
     * @return mixed
     */
    public function restore(User $user, Plantilla $plantilla)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Plantilla  $plantilla
     * @return mixed
     */
    public function forceDelete(User $user, Plantilla $plantilla)
    {
        //
    }
}
