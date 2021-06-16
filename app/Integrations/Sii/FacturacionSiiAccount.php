<?php

/**
 * Integration with Facturacion Sii REST Api
 *
 */
namespace App\Integrations\Sii;

use Illuminate\Support\Facades\Crypt;
use Exception;
use App\Empresa;

class FacturacionSiiAccount
{
    /**
     * Email de la cuenta de Facturacion Sii
     * 
     * @var string
     */
    private $email;

    /**
     * Contraseña de la cuenta de Facturacion Sii
     * 
     * @var string
     */
    private $password;

    /**
     * Si la cuenta en uso son las credenciales por defecto del sistema
     * o son de una Empresa
     * 
     * @var bool
     */
    public $default = true;

    /**
     * Establecer la cuenta a usar de la Empresa especificada
     * 
     * @param \App\Empresa|null  $empresa
     */
    public function setEmpresaAccount(Empresa $empresa = null)
    {
      if(is_null($empresa)){
        return $this->setDefaultAccount();
      }

      if(!$this->hasValidSiiAccount($empresa)){
        throw new Exception('La Empresa no tiene una cuenta Sii configurada.');
      }

      $email = $empresa->configuracion->sii_account->email;
      $password = Crypt::decryptString($empresa->configuracion->sii_account->password);

      return $this->setCredentials($email, $password);
    }

    /**
     * Establecer credenciales por defecto
     */
    public function setDefaultAccount()
    {
      $email = config('integraciones.sii.email');
      $password = config('integraciones.sii.password');

      if(is_null($email) || is_null($password)){
        throw new Exception('No hay una cuenta Sii por defecto configurada.');
      }

      return $this->setCredentials($email, $password, true);
    }

    /**
     * Establecer credenciales a usar en la cuenta
     * 
     * @param string  $email
     * @param string  $password
     */
    public static function setCustomCredentials(string $email, string $password)
    {
      $account = new static;

      return $account->setCredentials($email, $password);
    }

    /**
     * Establecer credenciales a usar en la cuenta
     * 
     * @param string  $email
     * @param string  $password
     * @param bool  $default
     */
    protected function setCredentials(string $email, string $password, bool $default = false)
    {
      $this->email = $email;
      $this->password = $password;
      $this->default = $default;

      return $this;
    }

    /**
     * Evaluar si la cuenta en uso usa las crecendiales por defecto del sistema
     * 
     * @return bool
     */
    public function isDefault()
    {
      return $this->default;
    }

    /**
     * Obtener el email (identifier)
     * 
     * @return string
     */
    public function getIdentifier()
    {
      return $this->email;
    }

    /**
     * Obtener el password enctriptado
     * 
     * @return string
     */
    public function getPassword()
    {
      return $this->password;
    }

    /**
     * Evaluar si la Empresa tiene una cuenta Sii configurada
     * 
     * @param  \App\Empresa  $empresa
     * @return bool
     */
    private function hasValidSiiAccount(Empresa $empresa)
    {
      return $empresa->configuracion->hasSiiAccount();
    }
}
