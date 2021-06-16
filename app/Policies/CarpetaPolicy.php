<?php

namespace App\Policies;

use App\Carpeta;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarpetaPolicy
{
    use HandlesAuthorization;

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
     * Determinar si el User puede crear una Carpeta de la seccion de Archivos
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function createArchivoCarpeta(User $user)
    {
      return $user->hasPermission('archivo-create');
    }

    /**
     * Determinar si el User puede ver la Carpeta de la seccion de Archivos
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function viewArchivoCarpeta(User $user, Carpeta $carpeta)
    {
      return (
        $user->hasPermission('archivo-view') ||
        $carpeta->archivoUsers()->wherePivot('user_id', $user->id)->exists() ||
        $carpeta->isPublic()
      ) &&
      $carpeta->isArchivo();
    }

    /**
     * Determinar si el User puede editar la Carpeta de la seccion de Archivos
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function updateArchivoCarpeta(User $user, Carpeta $carpeta)
    {
      return $user->hasPermission('archivo-edit') && $carpeta->isArchivo();;
    }

    /**
     * Determinar si el User puede eliminar la Carpeta de la seccion de Archivos
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function deleteArchivoCarpeta(User $user, Carpeta $carpeta)
    {
      return $user->hasPermission('archivo-delete') && $carpeta->isArchivo();;
    }
}
