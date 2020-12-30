<?php

namespace App\Policies;

use App\User;
use App\Solicitud;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitudPolicy
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
      if($user->isStaff()){
        return $user->hasPermission('solicitud-index');
      }

      return true;
    }

    /**
     * Determine whether the user can view the solicitud.
     *
     * @param  \App\User  $user
     * @param  \App\Solicitud  $solicitud
     * @return mixed
     */
    public function view(User $user, Solicitud $solicitud)
    {
      if($user->isStaff()){
        return $user->hasPermission('solicitud-view');
      }

      return ($user->empleado->id == $solicitud->empleado_id);
    }

    /**
     * Determine whether the user can create solicituds.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      if($user->isStaff()){
        return $user->hasPermission('solicitud-create');
      }

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
      if($user->isStaff()){
        return $user->hasPermission('solicitud-edit');
      }

      return ($user->empleado->id == $solicitud->empleado_id) && $solicitud->isPendiente();
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
      if($user->isStaff()){
        return $user->hasPermission('solicitud-delete');
      }

      return ($user->empleado->id == $solicitud->empleado_id);
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
