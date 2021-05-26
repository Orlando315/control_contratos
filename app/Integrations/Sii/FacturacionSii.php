<?php

/**
 * Integration with Facturacion Sii REST Api
 *
 */
namespace App\Integrations\Sii;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Auth, Log, Http};
use App\Empresa;

class FacturacionSii
{
    private $sandbox = false;
    private $baseUrl = '';
    private $identifier;
    private $password;
    private $token = null;

    public function __construct()
    {
      $this->sandbox = config('integraciones.sii.sandbox');
      $this->baseUrl = $this->sandbox ? config('integraciones.sii.sandbox_url') : config('integraciones.sii.url');
      $this->identifier = config('integraciones.sii.identifier');
      $this->password = config('integraciones.sii.password');
      $this->setApiToken();
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
    private function setApiToken()
    {
      $endpoint = $this->buildEndpoint('auth/local');

      $response = Http::post($endpoint, [
        'identifier'=> $this->identifier,
        'password'=> $this->password,
      ]);

      if($response->successful()){
        $this->token = $response['jwt'];
      }else{
        Log::channel('integrations')
          ->info('Error de autenticación con SII', [
            'user' => Auth::id(),
            'identifier' => $this->identifier,
            'response' => $response->json(),
          ]);
      }
    }

    /**
     * Obtener el api-key
     * 
     * @return string
     */
    private function getApiKey()
    {
      return $this->siiApiKey;
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
     * @return string
     */
    private function buildEndpoint(string $endpoint)
    {
      $baseUrl = Str::finish($this->baseUrl, '/');
      $endpoint = Str::startsWith($endpoint, '/') ? mb_substr($endpoint, 1) : $endpoint;

      return $baseUrl.$endpoint;
    }

    /**
     * Obtener informacion del receptor con el rut y digito validador (dv) especificados
     * 
     * @param  string  $rut
     * @param  string  $dv
     * @return array
     */
    public function busquedaReceptor($rut, $dv)
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

      if($response->successful()){
        return [true, $response->json()];
      }

      // devolver error de la api
      if(!$response->successful() && isset($response['message'])){
        return [false, $response['message']];
      }

      return [false, 'Ha ocurrido un error al consultar la información de la Empresa'];
    }

    /**
     * Preparar una factura en la Api
     * 
     * @return array
     */
    private function cargarFactura()
    {
      $endpoint = $this->buildEndpoint('factura/cargar');

      $response = Http::withHeaders([
        'api-key' => $this->getApiKey(),
      ])
      ->withToken($this->getToken())
      ->get($endpoint);

      if($response->successful() && isset($response['facturaId'])){
        return [true, $response['facturaId']];
      }

      // devolver error de la api
      if(!$response->successful() && isset($response['message'])){
        return [false, $response['message']];
      }

      return [false, 'Ha ocurrido un error con la API'];
    }

    /**
     * Agregar el receptor con los datos especificados a una factura
     * 
     * @param  string  $rut
     * @param  string  $dv
     * @return array
     */
    private function cargarReceptor($rut, $dv)
    {
      // Si la factura se carga correctamente
      // en $data estara el id de la factura
      [$response, $data] = $this->cargarFactura();

      if(!$response){
        return [false, $data];
      }

      $endpoint = $this->buildEndpoint('factura/'.$data.'/receptor/'.$rut.'/'.$dv);

      $response = Http::withHeaders([
        'api-key' => $this->getApiKey(),
      ])
      ->withToken($this->getToken())
      ->get($endpoint);

      if($response->successful() && isset($response['receptor'])){
        return [true, $data];
      }

      // devolver error de la api
      if(!$response->successful() && isset($response['message'])){
        return [false, $response['message']];
      }

      return [false, 'Ha ocurrido un error con la API'];
    }

    /**
     * Agregar el receptor con los datos especificados a una factura
     *
     * @param  string  $rut
     * @param  string  $dv
     * @param  array  $dataFactura
     * @return array
     */
    public function facturar($rut, $dv, $dataFactura)
    {
      // Si el receptor se carga a la factura correctamente
      // en $data estara el id de la factura
      [$response, $data] = $this->cargarReceptor($rut, $dv);

      if(!$response){
        return [false, $data];
      }

      $endpoint = $this->buildEndpoint('factura/'.$data.'/validar');
      $dataFactura['firma'] = $this->getFirma();

      $response = Http::withHeaders([
        'api-key' => $this->getApiKey(),
      ])
      ->withToken($this->getToken())
      ->post($endpoint, $dataFactura);

      if($response->successful()){
        return [true, $data];
      }

      // devolver error de la api
      if(!$response->successful() && isset($response['message'])){
        return [false, $response['message']];
      }

      return [false, 'Ha ocurrido un error con la API'];
    }

    /**
     * Obtener la "facturas recibidas" en la Api
     *
     * @param  int  $page
     * @return array
     */
    public function facturasRecibidas($page = 1)
    {
      $endpoint = $this->buildEndpoint('facturas/recibidas/'.$page);

      $response = Http::withHeaders([
        'api-key' => $this->getApiKey(),
      ])
      ->withToken($this->getToken())
      ->get($endpoint);

      if($response->successful() && isset($response['recibidas'])){
        return [true, $response['recibidas']];
      }

      // devolver error de la api
      if(!$response->successful() && isset($response['message'])){
        return [false, $response['message']];
      }

      return [false, 'Ha ocurrido un error con la API'];
    }

    /**
     * Obtener la informacion de una factura por el codigo proporcionado
     * 
     * @param  string  $codigo
     * @return array
     */
    public function consultaFactura($codigo)
    {
      $endpoint = $this->buildEndpoint('facturas/recibidas/ver/'.$codigo);

      $response = Http::withHeaders([
        'api-key' => $this->getApiKey(),
      ])
      ->withToken($this->getToken())
      ->get($endpoint);

      if($response->successful() && isset($response['productos'])){
        return $response->json();
      }

      return [];
    }
}
