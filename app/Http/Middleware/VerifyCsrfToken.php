<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
      'auth',
      'logout',
      'admin/variable/*',
      'admin/development/variable/*',
      'admin/documentos/*',
      'admin/usuarios/*/get',
      'admin/*/busqueda/sii',
      'admin/cotizacion/*/productos',
      'terminos',
    ];
}
