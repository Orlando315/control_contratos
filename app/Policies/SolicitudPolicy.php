<?php

namespace App\Policies;

use App\User;
use App\Solicitud;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitudPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the solicitud.
     *
     * @param  \App\User  $user
     * @param  \App\Solicitud  $solicitud
     * @return mixed
     */
    public function view(User $user, Solicitud $solicitud)
    {
      return $user->isEmpleado() && ($user->empleado->id == $solicitud->empleado_id);
    }

    /**
     * Determine whether the user can create solicituds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return true;
    }

    /**
     * Determine whether the user can update the solicitud.
     *
     * @param  \App\User  $user
     * @param  \App\Solicitud  $solicitud
     * @return mixed
     */
    public function update(User $user, Solicitud $solicitud)
    {
      return $user->isEmpleado() && ($user->empleado->id == $solicitud->empleado_id) && $solicitud->isPendiente();
    }

    /**
     * Determine whether the user can delete the solicitud.
     *
     * @param  \App\User  $user
     * @param  \App\Solicitud  $solicitud
     * @return mixed
     */
    public function delete(User $user, Solicitud $solicitud)
    {
      return $user->isEmpleado() && ($user->empleado->id == $solicitud->empleado_id);
    }

    /**
     * Determine whether the user can restore the solicitud.
     *
     * @param  \App\User  $user
     * @param  \App\Solicitud  $solicitud
     * @return mixed
     */
    public function restore(User $user, Solicitud $solicitud)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the solicitud.
     *
     * @param  \App\User  $user
     * @param  \App\Solicitud  $solicitud
     * @return mixed
     */
    public function forceDelete(User $user, Solicitud $solicitud)
    {
        //
    }
}
