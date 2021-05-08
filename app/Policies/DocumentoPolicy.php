<?php

namespace App\Policies;

use App\Documento;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentoPolicy
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
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function view(User $user, Documento $documento)
    {
        //
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
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function update(User $user, Documento $documento)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function delete(User $user, Documento $documento)
    {
        //
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
