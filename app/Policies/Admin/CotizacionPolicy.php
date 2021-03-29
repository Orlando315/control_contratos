<?php

namespace App\Policies\Admin;

use App\Cotizacion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CotizacionPolicy
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
      return $user->hasPermission('cotizacion-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function view(User $user, Cotizacion $cotizacion)
    {
      return $user->hasPermission('cotizacion-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('cotizacion-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function update(User $user, Cotizacion $cotizacion)
    {
      return $user->hasPermission('cotizacion-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function delete(User $user, Cotizacion $cotizacion)
    {
      return $user->hasPermission('cotizacion-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function restore(User $user, Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Cotizacion  $cotizacion
     * @return mixed
     */
    public function forceDelete(User $user, Cotizacion $cotizacion)
    {
        //
    }
}
