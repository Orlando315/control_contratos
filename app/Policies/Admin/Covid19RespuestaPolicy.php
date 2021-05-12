<?php

namespace App\Policies\Admin;

use App\Covid19Respuesta;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Covid19RespuestaPolicy
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
      return $user->hasPermission('covid19-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Covid19Respuesta  $respuesta
     * @return mixed
     */
    public function view(User $user, Covid19Respuesta $respuesta)
    {
      return $user->hasPermission('covid19-view');
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
     * @param  \App\Covid19Respuesta  $respuesta
     * @return mixed
     */
    public function update(User $user, Covid19Respuesta $respuesta)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Covid19Respuesta  $respuesta
     * @return mixed
     */
    public function delete(User $user, Covid19Respuesta $respuesta)
    {
      return $user->hasPermission('covid19-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Covid19Respuesta  $respuesta
     * @return mixed
     */
    public function restore(User $user, Covid19Respuesta $respuesta)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Covid19Respuesta  $respuesta
     * @return mixed
     */
    public function forceDelete(User $user, Covid19Respuesta $respuesta)
    {
        //
    }
}
