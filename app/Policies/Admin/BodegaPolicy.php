<?php

namespace App\Policies\Admin;

use App\Bodega;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BodegaPolicy
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
      return $user->hasPermission('bodega-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Bodega  $bodega
     * @return mixed
     */
    public function view(User $user, Bodega $bodega)
    {
      return $user->hasPermission('bodega-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('bodega-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Bodega  $bodega
     * @return mixed
     */
    public function update(User $user, Bodega $bodega)
    {
      return $user->hasPermission('bodega-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Bodega  $bodega
     * @return mixed
     */
    public function delete(User $user, Bodega $bodega)
    {
      return $user->hasPermission('bodega-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Bodega  $bodega
     * @return mixed
     */
    public function restore(User $user, Bodega $bodega)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Bodega  $bodega
     * @return mixed
     */
    public function forceDelete(User $user, Bodega $bodega)
    {
        //
    }
}
