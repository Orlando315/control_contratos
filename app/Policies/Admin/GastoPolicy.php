<?php

namespace App\Policies\Admin;

use App\Gasto;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GastoPolicy
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
      return $user->hasPermission('gasto-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Gasto  $gasto
     * @return mixed
     */
    public function view(User $user, Gasto $gasto)
    {
      return $user->hasPermission('gasto-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('gasto-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Gasto  $gasto
     * @return mixed
     */
    public function update(User $user, Gasto $gasto)
    {
      return $user->hasPermission('gasto-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Gasto  $gasto
     * @return mixed
     */
    public function delete(User $user, Gasto $gasto)
    {
      return $user->hasPermission('gasto-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Gasto  $gasto
     * @return mixed
     */
    public function restore(User $user, Gasto $gasto)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Gasto  $gasto
     * @return mixed
     */
    public function forceDelete(User $user, Gasto $gasto)
    {
        //
    }
}
