<?php

namespace App\Policies\Admin;

use App\Proveedor;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProveedorPolicy
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
      return $user->hasPermission('proveedor-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Proveedor  $proveedor
     * @return mixed
     */
    public function view(User $user, Proveedor $proveedor)
    {
      return $user->hasPermission('proveedor-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('proveedor-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Proveedor  $proveedor
     * @return mixed
     */
    public function update(User $user, Proveedor $proveedor)
    {
      return $user->hasPermission('proveedor-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Proveedor  $proveedor
     * @return mixed
     */
    public function delete(User $user, Proveedor $proveedor)
    {
      return $user->hasPermission('proveedor-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Proveedor  $proveedor
     * @return mixed
     */
    public function restore(User $user, Proveedor $proveedor)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Proveedor  $proveedor
     * @return mixed
     */
    public function forceDelete(User $user, Proveedor $proveedor)
    {
        //
    }
}
