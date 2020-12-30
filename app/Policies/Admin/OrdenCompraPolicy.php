<?php

namespace App\Policies\Admin;

use App\OrdenCompra;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrdenCompraPolicy
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
      return $user->hasPermission('compra-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrdenCompra  $compra
     * @return mixed
     */
    public function view(User $user, OrdenCompra $compra)
    {
      return $user->hasPermission('compra-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('compra-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrdenCompra  $compra
     * @return mixed
     */
    public function update(User $user, OrdenCompra $compra)
    {
      return $user->hasPermission('compra-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrdenCompra  $compra
     * @return mixed
     */
    public function delete(User $user, OrdenCompra $compra)
    {
      return $user->hasPermission('compra-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrdenCompra  $compra
     * @return mixed
     */
    public function restore(User $user, OrdenCompra $compra)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\OrdenCompra  $compra
     * @return mixed
     */
    public function forceDelete(User $user, OrdenCompra $compra)
    {
        //
    }
}
