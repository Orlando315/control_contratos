<?php

namespace App\Policies;

use App\User;
use App\Anticipo;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnticipoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the anticipo.
     *
     * @param  \App\User  $user
     * @param  \App\Anticipo  $anticipo
     * @return mixed
     */
    public function view(User $user, Anticipo $anticipo)
    {
        //
    }

    /**
     * Determine whether the user can create anticipos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
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
        //
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
