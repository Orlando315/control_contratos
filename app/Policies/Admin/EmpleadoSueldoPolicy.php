<?php

namespace App\Policies\Admin;

use App\EmpleadosSueldo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpleadoSueldoPolicy
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
      return $user->hasPermission('sueldo-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return mixed
     */
    public function view(User $user, EmpleadosSueldo $sueldo)
    {
      return ($user->isAdmin() && $user->hasPermission('sueldo-view')) || ($user->isNotAdmin() && $user->empleado->id == $sueldo->empleado_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('sueldo-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return mixed
     */
    public function update(User $user, EmpleadosSueldo $sueldo)
    {
      return $user->hasPermission('sueldo-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return mixed
     */
    public function delete(User $user, EmpleadosSueldo $sueldo)
    {
      return $user->hasPermission('sueldo-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return mixed
     */
    public function restore(User $user, EmpleadosSueldo $sueldo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\EmpleadosSueldo  $sueldo
     * @return mixed
     */
    public function forceDelete(User $user, EmpleadosSueldo $sueldo)
    {
        //
    }
}
