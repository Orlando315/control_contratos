<?php

namespace App\Policies\Admin;

use App\Pago;
use App\User;
use App\Facturacion;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagoPolicy
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
      return $user->hasPermission('pago-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pago  $pago
     * @return mixed
     */
    public function view(User $user, Pago $pago)
    {
      return $user->hasPermission('pago-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @param  \App\Facturacion  $facturacion
     * @return mixed
     */
    public function create(User $user, Facturacion $facturacion)
    {
      return $user->hasPermission('pago-create') && !$facturacion->isPaga();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pago  $pago
     * @return mixed
     */
    public function update(User $user, Pago $pago)
    {
      return $user->hasPermission('pago-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pago  $pago
     * @return mixed
     */
    public function delete(User $user, Pago $pago)
    {
      return $user->hasPermission('pago-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pago  $pago
     * @return mixed
     */
    public function restore(User $user, Pago $pago)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Pago  $pago
     * @return mixed
     */
    public function forceDelete(User $user, Pago $pago)
    {
        //
    }
}
