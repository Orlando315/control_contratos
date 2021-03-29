<?php

namespace App\Policies;

use App\Ayuda;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AyudaPolicy
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
     * @param  \App\Ayuda  $ayuda
     * @return mixed
     */
    public function view(User $user, Ayuda $ayuda)
    {
      return $ayuda->isActive() && $ayuda->roles->contains($user->role()->id);
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
     * @param  \App\Ayuda  $ayuda
     * @return mixed
     */
    public function update(User $user, Ayuda $ayuda)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ayuda  $ayuda
     * @return mixed
     */
    public function delete(User $user, Ayuda $ayuda)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ayuda  $ayuda
     * @return mixed
     */
    public function restore(User $user, Ayuda $ayuda)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ayuda  $ayuda
     * @return mixed
     */
    public function forceDelete(User $user, Ayuda $ayuda)
    {
        //
    }
}
