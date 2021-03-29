<?php

namespace App\Policies\Admin;

use App\Requisito;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequisitoPolicy
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
      return $user->hasPermission('requisito-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Requisito  $requisito
     * @return mixed
     */
    public function view(User $user, Requisito $requisito)
    {
      return $user->hasPermission('requisito-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('requisito-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Requisito  $requisito
     * @return mixed
     */
    public function update(User $user, Requisito $requisito)
    {
      return $user->hasPermission('requisito-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Requisito  $requisito
     * @return mixed
     */
    public function delete(User $user, Requisito $requisito)
    {
      return $user->hasPermission('requisito-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Requisito  $requisito
     * @return mixed
     */
    public function restore(User $user, Requisito $requisito)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Requisito  $requisito
     * @return mixed
     */
    public function forceDelete(User $user, Requisito $requisito)
    {
        //
    }
}
