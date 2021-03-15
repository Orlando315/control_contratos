<?php

namespace App\Policies;

use App\RequerimientoMaterial;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequerimientoMaterialPolicy
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
      return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\RequerimientoMaterial   $requerimiento
     * @return mixed
     */
    public function view(User $user, RequerimientoMaterial  $requerimiento)
    {
      if(($user->id == $requerimiento->solicitante) || ($user->id == $requerimiento->dirigido) || ($requerimiento->firmantes->contains($user))){
        return true;
      }

      return $user->hasPermission('requerimiento-material-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\RequerimientoMaterial   $requerimiento
     * @return mixed
     */
    public function update(User $user, RequerimientoMaterial  $requerimiento)
    {
      if(($user->id == $requerimiento->solicitante || $requerimiento->userIsFirmante()) && $requerimiento->isPendiente()){
        return true;
      }

      return $user->hasPermission('requerimiento-material-edit') && $requerimiento->isPendiente();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\RequerimientoMaterial   $requerimiento
     * @return mixed
     */
    public function delete(User $user, RequerimientoMaterial  $requerimiento)
    {
      if($user->id == $requerimiento->solicitante){
        return true;
      }

      return $user->hasPermission('requerimiento-material-delete');
    }

    /**
     * Determinar si el user puede aprobar como firmante
     *
     * @param  \App\User  $user
     * @param  \App\RequerimientoMaterial   $requerimiento
     * @return mixed
     */
    public function approve(User $user, RequerimientoMaterial  $requerimiento)
    {
      return $requerimiento->firmantes->contains($user);
    }
}
