<?php

namespace App\Policies\Admin;

use App\InventarioEntrega;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioEntregaPolicy
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
      return $user->hasPermission('inventario-entrega-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioEntrega  $entrega
     * @return mixed
     */
    public function view(User $user, InventarioEntrega $entrega)
    {
      return $user->hasPermission('inventario-entrega-view');
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
     * @param  \App\InventarioEntrega  $entrega
     * @return mixed
     */
    public function update(User $user, InventarioEntrega $entrega)
    {
      return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioEntrega  $entrega
     * @return mixed
     */
    public function delete(User $user, InventarioEntrega $entrega)
    {
      return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioEntrega  $entrega
     * @return mixed
     */
    public function restore(User $user, InventarioEntrega $entrega)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioEntrega  $entrega
     * @return mixed
     */
    public function forceDelete(User $user, InventarioEntrega $entrega)
    {
        //
    }
}
