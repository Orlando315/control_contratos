<?php

/**
 * Integration with Facturacion Sii REST Api
 *
 */
namespace App\Integrations;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Auth, Log, Http};
use App\Empresa;

class FacturacionSii
{
    private $sandbox = null;
    private $url = '';
    private $sii_clave;
    private $sii_clave_certificado;
    private $rut;
    private $dv;
    private $token = null;
    private $firma = null;

    public function __construct(Empresa $empresa = null)
    {
      $empresa = $empresa ?? Auth::user()->empresa;
      $this->rut = $empresa->getRut();
      $this->dv = $empresa->getRutDv();
      $this->sandbox = config('integraciones.sii.sandbox');
      $this->url = $this->sandbox ? config('integraciones.sii.sandbox_url') : config('integraciones.sii.url');
      $this->sii_clave = $empresa->configuracion->sii_clave;
      $this->sii_clave_certificado = $empresa->configuracion->sii_clave_certificado;
      $this->firma = $empresa->configuracion->firma;
      $this->setApiToken();
    }

    /**
     * Evaluar si es posible conectarse con Paypal
     * 
     * @return bool
     */
    public function isActive()
    {
      return !is_null($this->token);
    }

    /**
     * Obtener y establecer el Api Token
     *
     * @return void
     */
    private function setApiToken()
    {
      $endpoint = $this->buildEndpoint('login');

      $response = Http::WithHeaders([
        'api-key' => $this->sii_clave_certificado,
      ])
      ->post($endpoint, [
        'rut' => $this->rut,
        'dv' => $this->dv,
        'clave' => $this->sii_clave,
      ]);

      if($response->successful()){
        $this->token = $response['token'];
      }else{
        Log::channel('integrations')
          ->info('Error de autenticación con SII', [
            'user' => Auth::id(),
            'rut' => $this->rut,
            'dv' => $this->dv,
            'response' => $response->json(),
          ]);
      }
    }

    /**
     * Obtener clave de certificado de Sii
     * 
     * @return string
     */
    private function getSiiClave()
    {
      return $this->sii_clave_certificado;
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
     * Obtener la firma
     * 
     * @return string
     */
    private function getFirma()
    {
      return $this->firma;
    }

    /**
     * Construir la url con el endpoint especificado
     * 
     * @param  string  $endpoint
     * @return string
     */
    private function buildEndpoint(string $endpoint)
    {
      $baseUrl = Str::finish($this->url, '/');
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
      $endpoint = $this->buildEndpoint('receptor/buscar/'.$rut.'/'.$dv);

      $response = Http::withHeaders([
        'api-key' => $this->getSiiClave(),
      ])
      ->withToken($this->getToken())
      ->get($endpoint);

      if($response->successful()){
        return [true, $response['receptor']];
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
        'api-key' => $this->getSiiClave(),
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
        'api-key' => $this->getSiiClave(),
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
        'api-key' => $this->getSiiClave(),
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
        'api-key' => $this->getSiiClave(),
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
        'api-key' => $this->getSiiClave(),
      ])
      ->withToken($this->getToken())
      ->get($endpoint);

      if($response->successful() && isset($response['productos'])){
        return $response->json();
      }

      return [];
    }
}
