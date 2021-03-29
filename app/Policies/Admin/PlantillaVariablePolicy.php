<?php

namespace App\Policies\Admin;

use App\User;
use App\PlantillaVariable;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlantillaVariablePolicy
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
     * Determine whether the user can view the plantilla variable.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaVariable  $variable
     * @return mixed
     */
    public function view(User $user, PlantillaVariable $variable)
    {
      return $user->hasPermission('plantilla-variable-view');
    }

    /**
     * Determine whether the user can create plantilla variables.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('plantilla-variable-create');
    }

    /**
     * Determine whether the user can update the plantilla variable.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaVariable  $variable
     * @return mixed
     */
    public function update(User $user, PlantillaVariable $variable)
    {
      return $user->hasPermission('plantilla-variable-edit') && !$variable->isStatic();
    }

    /**
     * Determine whether the user can delete the plantilla variable.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaVariable  $variable
     * @return mixed
     */
    public function delete(User $user, PlantillaVariable $variable)
    {
      return $user->hasPermission('plantilla-variable-delete') && !$variable->isStatic();
    }

    /**
     * Determine whether the user can restore the plantilla variable.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaVariable  $variable
     * @return mixed
     */
    public function restore(User $user, PlantillaVariable $variable)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the plantilla variable.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaVariable  $variable
     * @return mixed
     */
    public function forceDelete(User $user, PlantillaVariable $variable)
    {
        //
    }
}
