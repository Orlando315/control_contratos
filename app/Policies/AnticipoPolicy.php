<?php

namespace App\Policies;

use App\User;
use App\Anticipo;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnticipoPolicy
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
      if($user->hasRole('developer|superadmin|empresa')){
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
      return $user->hasPermission('anticipo-index');
    }

    /**
     * Determine whether the user can view the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function view(User $user, Anticipo $anticipo)
    {
      return $user->isEmpleado() || $user->hasPermission('anticipo-view');
    }

    /**
     * Determine whether the user can create anticipos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->isEmpleado() || $user->hasPermission('anticipo-create');
    }

    /**
     * Determine whether the user can update the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function update(User $user, Anticipo $anticipo)
    {
      return !$anticipo->isRechazado();
    }

    /**
     * Determine whether the user can delete the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function delete(User $user, Anticipo $anticipo)
    {
      return $user->hasPermission('anticipo-delete');
    }

    /**
     * Determine whether the user can delete the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function deleteSerie(User $user)
    {
      return $user->hasPermission('anticipo-delete');
    }

    /**
     * Determine whether the user can restore the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function restore(User $user, Anticipo $anticipo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function forceDelete(User $user, Anticipo $anticipo)
    {
        //
    }
}
