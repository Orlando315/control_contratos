<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Log, Http};

class ConfiguracionEmpresa extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configuracion_empresas';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'jornada',
      'dias_vencimiento',
      'clave_sii',
      'sii_clave_certificado',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'sii_clave',
      'sii_clave_certificado',
    ];

    /**
     * Integraciones con sus respectivos campos
     *
     * @var array
     */
    private $_integrations = [
      'sii' => [
        'sii_clave',
        'sii_clave_certificado',
      ],
    ];

    /**
     * Obtener la Empresa a la que pertenece la Configuracion
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Evaluar si la integracion especificada tiene todos los datos necesarios
     *
     * @param  string  $integration
     * @return bool
     */
    public function isIntegrationComplete($integration)
    {
      $attributes = array_values($this->only($this->_integrations[$integration]));

      return !in_array(null, $attributes, true);
    }

    /**
     * Evaluar si la integracion especificada, no tiene todos los datos necesarios
     *
     * @param  string  $integration
     * @return bool
     */
    public function isIntegrationIncomplete($integration)
    {
      return !$this->isIntegrationComplete($integration);
    }

    /**
     * Obtener token de autenticacion de Facturacion Sii
     * 
     * @return mixed
     */
    public function getSiiToken()
    {
      if($this->isIntegrationIncomplete('sii')){
        return false;
      }

      $baseUrl = config('integraciones.sii.sandbox') ? config('integraciones.sii.sandbox_url') : config('integraciones.sii.url');
      $endpoint = 'login';
      $url = $baseUrl.$endpoint;

      $rut = $this->empresa->getRut();
      $dv = $this->empresa->getRutDv();

      try{
        $response = Http::WithHeaders([
          'api-key' => $this->sii_clave_certificado,
        ])
        ->post($url, [
          'rut' => $rut,
          'dv' => $dv,
          'clave' => $this->sii_clave,
        ]);
      }catch(\Exception $e){
        Log::channel('integrations')
          ->info('Error de autenticación con SII', [
            'user' => Auth::id(),
            'rut' => $rut,
            'dv' => $dv,
            'error' => $e->getMessage(),
            'sii' => [
              'sandbox' => config('integraciones.sii.sandbox'),
              'url' => $baseUrl,
            ]
          ]);
        abort(500, 'Ha ocurrido un error inesperado al realizar la petición.');
      }

      return $response->successful() ? $response['token'] : false;
    }

    /**
     * Obtener informacion de la empresa de la API de Facturacion Sii
     * con el rut y digito validador (dv) especificado
     * 
     * @param  string  $rut
     * @param  string  $dv
     * @return array
     */
    public function getEmpresaFromSii($rut, $dv)
    {
      $token = $this->getSiiToken();
      if(!$token){
        return [false, 'Error de autenticación con Facturación Sii.'];
      }

      $baseUrl = config('integraciones.sii.sandbox') ? config('integraciones.sii.sandbox_url') : config('integraciones.sii.url');
      $endpoint = 'receptor/buscar/';
      $url = $baseUrl.$endpoint.$rut.'/'.$dv;

      try{
        $response = Http::withHeaders([
          'api-key' => $this->sii_clave_certificado,
        ])
        ->withToken($token)
        ->get($url);
      }catch(\Exception $e){
        Log::channel('integrations')
          ->info('Error de autenticación con SII', [
            'user' => Auth::id(),
            'rut' => $rut,
            'dv' => $dv,
            'error' => $e->getMessage(),
            'sii' => [
              'sandbox' => config('integraciones.sii.sandbox'),
              'url' => $baseUrl,
            ]
          ]);
        abort(500, 'Ha ocurrido un error inesperado al realizar la petición.');
      }

      if($response->successful()){
        return [true, $response['receptor']];
      }

      // devolver error de la api
      if(!$response->successful() && isset($response['message'])){
        return [false, $response['message']];
      }

      return [false, 'Ha ocurrido un error al consultar la información de la Empresa'];
    }
}
