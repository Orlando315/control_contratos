<?php

namespace App\Policies\Admin;

use App\PlantillaDocumento;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlantillaDocumentoPolicy
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
      return $user->hasPermission('plantilla-documento-index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaDocumento  $documento
     * @return mixed
     */
    public function view(User $user, PlantillaDocumento $documento)
    {
      return $user->hasPermission('plantilla-documento-view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
      return $user->hasPermission('plantilla-documento-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaDocumento  $documento
     * @return mixed
     */
    public function update(User $user, PlantillaDocumento $documento)
    {
      return $user->hasPermission('plantilla-documento-edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaDocumento  $documento
     * @return mixed
     */
    public function delete(User $user, PlantillaDocumento $documento)
    {
      return $user->hasPermission('plantilla-documento-delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaDocumento  $documento
     * @return mixed
     */
    public function restore(User $user, PlantillaDocumento $documento)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\PlantillaDocumento  $documento
     * @return mixed
     */
    public function forceDelete(User $user, PlantillaDocumento $documento)
    {
        //
    }
}
