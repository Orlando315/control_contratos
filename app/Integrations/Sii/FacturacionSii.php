<?php

/**
 * Integration with Facturacion Sii REST Api
 *
 */
namespace App\Integrations\Sii;

use Illuminate\Support\{Str, Arr};
use Illuminate\Support\Facades\{Auth, Log, Http};
use Exception;
use App\Integrations\Sii\FacturacionSiiAccount;
use App\Integrations\Sii\FacturacionSiiErrors;
use App\Empresa;

class FacturacionSii
{
    /**
     * Url basi para realizar peticiones a la API
     * 
     * @var string
     */
    private $baseUrl;

    /**
     * JWT para el realizar peticiones a la API
     * 
     * @var string
     */
    private $token;

    /**
     * Perfil de Sii de la cuenta en uso
     * 
     * @var array
     */
    protected $profile;

    /**
     * Si se ha logueado en Sii o no
     * 
     * @var bool
     */
    protected $logged = false;

    /**
     * Cuenta de Facturacion Sii a usar
     *
     * @var \App\Integrations\Sii\FacturacionSiiAccount
     */
    protected FacturacionSiiAccount $account;

    /**
     * [__construct description]
     * 
     * @param \App\Integrations\Sii\FacturacionSiiAccount|null $account
     */
    public function __construct(FacturacionSiiAccount $account = null)
    {
      $this->setBaseUrl();

      if($account){
        $this->useAccount($account);
      }
    }

    /**
     * Establecer la url base de la API
     *
     * @return $this
     */
    public function setBaseUrl($baseUrl = null)
    {
      $this->baseUrl = $baseUrl ?: config('integraciones.sii.url');

      return $this;
    }

    /**
     * Establecer la cuenta a usar para conectarse
     * 
     * @param  FacturacionSiiAccount $account
     */
    public function useAccount(FacturacionSiiAccount $account)
    {
      $this->account = $account;

      return $this;
    }

    /**
     * Evaluar si es posible conectarse con la API
     * 
     * @return bool
     */
    public function isActive()
    {
      return !is_null($this->token);
    }

    /**
     * Evaluar si no es posible conectarse con la API
     * 
     * @return bool
     */
    public function isInactive()
    {
      return !$this->isActive();
    }

    /**
     * Obtener y establecer el Api Token
     *
     * @return void
     */
    public function setToken()
    {
      $endpoint = $this->buildEndpoint('auth/local');

      $identifier = $this->account->getIdentifier();

      $response = Http::post($endpoint, [
        'identifier'=> $identifier,
        'password'=> $this->account->getPassword(),
      ]);

      if($response->failed()){
        $error = Arr::get($response->json(), 'message.0.messages.0.id');

        Log::channel('integrations')
          ->info('Error de autenticación con Sii', [
            'user' => Auth::id(),
            'identifier' => $identifier,
            'default' => $this->account->isDefault(),
            'response' => $response->json(),
          ]);

        throw new Exception(FacturacionSiiErrors::getErrorMessage($error));
      }

      $this->logged = true;
      $this->profile = $response['user'];
      $this->token = $response['jwt'];

      return $this;
    }

    /**
     * Obtener el Api Token
     * 
     * @return string
     */
    private function getToken()
    {
      return $this->token;
    }

    /**
     * Construir la url con el endpoint especificado
     * 
     * @param  string  $endpoint
     * @param  array  $params
     * @return string
     */
    private function buildEndpoint(string $endpoint, array $params = []): string
    {
      $baseUrl = Str::finish($this->baseUrl, '/');
      $endpoint = Str::startsWith($endpoint, '/') ? mb_substr($endpoint, 1) : $endpoint;

      if(Str::contains($endpoint, '?')){
        $endpoint = Str::replaceArray('?', $params, $endpoint);
      }

      return $baseUrl.$endpoint;
    }

    /**
     * Obtener informacion del receptor con el rut y digito validador (dv) especificados
     * 
     * @param  string  $rut
     * @param  string  $dv
     * @return array
     */
    public function busquedaReceptor(string $rut, string $dv)
    {
      $endpoint = $this->buildEndpoint('emits/information-receiver-default');

      $response = Http::withToken($this->getToken())
      ->post($endpoint, [
        'document' => [
          'receiver' => [
            'rut' => [
              'rut' => $rut,
              'dv' => $dv,
            ]
          ]
        ]
      ]);

      if($response->failed()){
        $error = Arr::get($response->json(), 'message');

        Log::channel('integrations')
          ->error('Error al consular RUT en Sii', [
            'user' => Auth::id(),
            'default' => $this->account->isDefault(),
            'logged' => $this->logged,
            'rut' => $rut,
            'dv' => $dv,
            'response' => $response->json(),
          ]);

        throw new Exception(FacturacionSiiErrors::getErrorMessage($error));
      }

      return $response->json();
    }

    /**
     * [checkLogin description]
     * 
     * @param  string  $email
     * @param  string  $password
     * @return array
     */
    public function checkLogin(string $email, string $password)
    {
      $account = FacturacionSiiAccount::setCustomCredentials($email, $password);

      return $this->useAccount($account)->getProfile();
    }

    /**
     * Obtener el perfil del la cuenta en uso
     */
    public function getProfile()
    {
      if($this->isInactive()){
        $this->setToken();
      }

      return $this->profile;
    }

    /**
     * Regitrar usuario en la API
     * 
     * @param  string  $username
     * @param  string  $email
     * @param  string  $password
     * @return array
     */
    public function registerUser(string $username, string $email, string $password)
    {
      $endpoint = $this->buildEndpoint('/auth/local/register');

      $data = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
      ];

      $response = Http::post($endpoint, $data);

      if($response->failed()){
        $error = Arr::get($response->json(), 'message.0.messages.0.id');

        Log::channel('integrations')
          ->error('Error al crear user Sii', [
            'user' => Auth::id(),
            'default' => $this->account->isDefault(),
            'data' => $data,
            'response' => $response->json(),
          ]);

        throw new Exception(FacturacionSiiErrors::getErrorMessage($error));
      }

      $data['id'] = $response->json()['user']['id'];

      return $data;
    }

    /**
     * Regitrar RUT en la API
     * 
     * @param  string  $rut
     * @param  string  $password
     * @param  string  $certificatePassword
     * @return array
     */
    public function registerRut(string $rut, string $password, string $certificatePassword)
    {
      $endpoint = $this->buildEndpoint('/ruts');

      $data = [
        'rut' => $rut,
        'password' => $password,
        'certificatePassword' => $certificatePassword,
      ];

      $response = Http::withToken($this->getToken())
      ->post($endpoint, $data);

      if($response->failed()){
        $error = Arr::get($response->json(), 'message.0.messages.0.id');

        Log::channel('integrations')
          ->error('Error al registrar RUT en Sii', [
            'user' => Auth::id(),
            'default' => $this->account->isDefault(),
            'rut' => $data,
            'response' => $response->json(),
          ]);

        throw new Exception(FacturacionSiiErrors::getErrorMessage($error));
      }

      $data['id'] = $response->json()['id'];

      return $data;
    }

    /**
     * Actualizar RUT de representante en la API
     *
     * @param  int  $id
     * @param  string  $rut
     * @param  string  $password
     * @param  string  $certificatePassword
     * @return array
     */
    public function updateRut(int $id, string $rut, string $password, string $certificatePassword)
    {
      $endpoint = $this->buildEndpoint('/ruts/?', [$id]);

      $data = [
        'rut' => $rut,
        'password' => $password,
        'certificatePassword' => $certificatePassword,
      ];

      $response = Http::withToken($this->getToken())
      ->put($endpoint, $data);

      if($response->failed()){
        $error = Arr::get($response->json(), 'message.0.messages.0.id');

        Log::channel('integrations')
          ->error('Error al actualizar RUT en Sii', [
            'user' => Auth::id(),
            'default' => $this->account->isDefault(),
            'rut' => $data,
            'response' => $response->json(),
          ]);

        throw new Exception(FacturacionSiiErrors::getErrorMessage($error));
      }

      $data['id'] = $response->json()['id'];

      return $data;
    }
}
