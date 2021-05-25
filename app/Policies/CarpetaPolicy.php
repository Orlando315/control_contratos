<?php

namespace App\Policies;

use App\Carpeta;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarpetaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function view(User $user, Carpeta $carpeta)
    {
      return ($user->empleado_id == $carpeta->carpetable_id) && $carpeta->isTypeEmpleado() && $carpeta->isVisible();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function update(User $user, Carpeta $carpeta)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function delete(User $user, Carpeta $carpeta)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function restore(User $user, Carpeta $carpeta)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function forceDelete(User $user, Carpeta $carpeta)
    {
        //
    }
}
