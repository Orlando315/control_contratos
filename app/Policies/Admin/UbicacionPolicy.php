<?php

namespace App\Policies\Admin;

use App\Ubicacion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UbicacionPolicy
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
      return $user->hasPermission('ubicacion-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ubicacion  $ubicacion
     * @return mixed
     */
    public function view(User $user, Ubicacion $ubicacion)
    {
      return $user->hasPermission('ubicacion-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('ubicacion-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ubicacion  $ubicacion
     * @return mixed
     */
    public function update(User $user, Ubicacion $ubicacion)
    {
      return $user->hasPermission('ubicacion-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ubicacion  $ubicacion
     * @return mixed
     */
    public function delete(User $user, Ubicacion $ubicacion)
    {
      return $user->hasPermission('ubicacion-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ubicacion  $ubicacion
     * @return mixed
     */
    public function restore(User $user, Ubicacion $ubicacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ubicacion  $ubicacion
     * @return mixed
     */
    public function forceDelete(User $user, Ubicacion $ubicacion)
    {
        //
    }
}
