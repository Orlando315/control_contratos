<?php

namespace App\Policies;

use App\Documento;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentoPolicy
{
    use HandlesAuthorization;

    /**
     * Determinar si el User puede ver el Documento de la seccion de Archivos
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function viewArchivoDocumento(User $user, Documento $documento)
    {
      return $user->hasPermission('archivo-view') && $documento->isArchivo();
    }

    /**
     * Determine whether the user can download the model.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function downloadArchivoDocumento(User $user, Documento $documento)
    {
      return (
        $user->hasPermission('archivo-view') ||
        $documento->archivoUsers()->wherePivot('user_id', $user->id)->exists() ||
        $documento->isPublic()
      ) &&
      $documento->isArchivo();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function createArchivoDocumento(User $user)
    {
      return $user->hasPermission('archivo-create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function updateArchivoDocumento(User $user, Documento $documento)
    {
      return $user->hasPermission('archivo-edit') && $documento->isArchivo();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function deleteArchivoDocumento(User $user, Documento $documento)
    {
      return $user->hasPermission('archivo-delete') && $documento->isArchivo();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function download(User $user, Documento $documento)
    {
      return $documento->canUserDownload();
    }
}
