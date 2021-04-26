<?php

namespace App\Policies;

use App\InventarioV2Egreso;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventarioV2EgresoPolicy
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
      return $user->hasPermission('inventario-egreso-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Egreso  $egreso
     * @return mixed
     */
    public function view(User $user, InventarioV2Egreso $egreso)
    {
      return $user->hasPermission('inventario-egreso-view') || ($user->id == $egreso->user_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('inventario-egreso-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Egreso  $egreso
     * @return mixed
     */
    public function update(User $user, InventarioV2Egreso $egreso)
    {
      return $user->hasPermission('inventario-egreso-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Egreso  $egreso
     * @return mixed
     */
    public function delete(User $user, InventarioV2Egreso $egreso)
    {
      return $user->hasPermission('inventario-egreso-delete');
    }

    /**
     * Evaluar si e User puede marcar como recibido el Egreso de InventarioV2.
     *
     * @param  \App\User  $user
     * @param  \App\InventarioV2Egreso  $egreso
     * @return mixed
     */
    public function accept(User $user, InventarioV2Egreso $egreso)
    {
      return $user->id == $egreso->user_id;
    }
}
