<?php

namespace App\Policies\Admin;

use App\Transporte;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransportePolicy
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
      return $user->hasPermission('transporte-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Transporte  $transporte
     * @return mixed
     */
    public function view(User $user, Transporte $transporte)
    {
      return $user->hasPermission('transporte-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('transporte-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Transporte  $transporte
     * @return mixed
     */
    public function update(User $user, Transporte $transporte)
    {
      return $user->hasPermission('transporte-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Transporte  $transporte
     * @return mixed
     */
    public function delete(User $user, Transporte $transporte)
    {
      return $user->hasPermission('transporte-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Transporte  $transporte
     * @return mixed
     */
    public function restore(User $user, Transporte $transporte)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Transporte  $transporte
     * @return mixed
     */
    public function forceDelete(User $user, Transporte $transporte)
    {
        //
    }
}
