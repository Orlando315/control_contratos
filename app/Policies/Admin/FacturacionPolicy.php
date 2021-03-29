<?php

namespace App\Policies\Admin;

use App\Facturacion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacturacionPolicy
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
      return $user->hasPermission('cotizacion-facturacion-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Facturacion  $facturacion
     * @return mixed
     */
    public function view(User $user, Facturacion $facturacion)
    {
      return $user->hasPermission('cotizacion-facturacion-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('cotizacion-facturacion-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Facturacion  $facturacion
     * @return mixed
     */
    public function update(User $user, Facturacion $facturacion)
    {
      return $user->hasPermission('cotizacion-facturacion-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Facturacion  $facturacion
     * @return mixed
     */
    public function delete(User $user, Facturacion $facturacion)
    {
      return $user->hasPermission('cotizacion-facturacion-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Facturacion  $facturacion
     * @return mixed
     */
    public function restore(User $user, Facturacion $facturacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Facturacion  $facturacion
     * @return mixed
     */
    public function forceDelete(User $user, Facturacion $facturacion)
    {
        //
    }
}
