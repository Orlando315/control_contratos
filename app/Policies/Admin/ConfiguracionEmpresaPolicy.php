<?php

namespace App\Policies\Admin;

use App\ConfiguracionEmpresa;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfiguracionEmpresaPolicy
{
    use HandlesAuthorization;

    /**
     * Determinar si el user puede iniciar sesion o crear la cuenta de Facturacion sii de la Empresa.
     *
     * @param  \App\User  $user
     * @param  \App\ConfiguracionEmpresa  $configuracion
     * @return mixed
     */
    public function createSsiAccount(User $user, ConfiguracionEmpresa $configuracion)
    {
      return $configuracion->doesntHaveSiiAccount();
    }

    /**
     * Determinar si el user puede editar la cuenta sii de la Empresa.
     *
     * @param  \App\User  $user
     * @param  \App\ConfiguracionEmpresa  $configuracion
     * @return mixed
     */
    public function editSsiAccount(User $user, ConfiguracionEmpresa $configuracion)
    {
      return $configuracion->hasSiiAccount();
    }

    /**
     * Determinar si el user puede asociar la informacion de un Representante en Facturacion Sii
     *
     * @param  \App\User  $user
     * @param  \App\ConfiguracionEmpresa  $configuracion
     * @return mixed
     */
    public function createSsiRepresentante(User $user, ConfiguracionEmpresa $configuracion)
    {
      return $configuracion->doesntHaveSiiRepresentante();
    }

    /**
     * Determinar si el user puede editar la informacion de un Representante en Facturacion Sii
     *
     * @param  \App\User  $user
     * @param  \App\ConfiguracionEmpresa  $configuracion
     * @return mixed
     */
    public function editSsiRepresentante(User $user, ConfiguracionEmpresa $configuracion)
    {
      return $configuracion->hasSiiRepresentante();
    }
}
