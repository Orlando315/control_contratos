<?php

namespace App\Policies\Admin;

use App\Factura;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacturaPolicy
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
      return $user->hasPermission('factura-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Factura  $factura
     * @return mixed
     */
    public function view(User $user, Factura $factura)
    {
      return $user->hasPermission('factura-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('factura-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Factura  $factura
     * @return mixed
     */
    public function update(User $user, Factura $factura)
    {
      return $user->hasPermission('factura-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Factura  $factura
     * @return mixed
     */
    public function delete(User $user, Factura $factura)
    {
      return $user->hasPermission('factura-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Factura  $factura
     * @return mixed
     */
    public function restore(User $user, Factura $factura)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Factura  $factura
     * @return mixed
     */
    public function forceDelete(User $user, Factura $factura)
    {
        //
    }
}
