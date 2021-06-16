<?php

namespace App\Integrations\Sii;

class FacturacionSiiErrors
{
    /**
     * Obtener el significado del error
     * 
     * @param  string  $error
     * @return string
     */
    public static function getErrorMessage($error): string
    {
      switch ($error) {
        case 'Auth.form.error.invalid':
          return 'Identificador o contraseña invalidos.';

        case 'Auth.form.error.password.provide':
          return 'Por favor introduce una contraseña';

        case 'Auth.form.error.email.provide':
          return 'Debe introducir un email.';
          break;

        case 'Auth.form.error.email.format':
          return 'Debe introducir un email valido.';
          break;

        case 'Auth.form.error.email.taken':
          return 'El email ya esta registrado.';
          break;

        case 'Auth.form.error.username.taken':
          return 'El usuario ya esta registrado.';
          break;

        case 'Auth.form.error.password.provide':
          return 'Debe introducir una contraseña.';
          break;

        case 'Auth.form.error.invalid':
          return 'Identificador or contraseña invalido.';
        
        default:
          return $error ?: 'Error desconcido de la API.';
          break;
      }
    }
}
