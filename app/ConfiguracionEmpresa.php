<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Log, Http};
use App\Integrations\FacturacionSii;

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
     * Obtener informacion de la empresa de la API de Facturacion Sii
     * con el rut y digito validador (dv) especificado
     * 
     * @param  string  $rut
     * @param  string  $dv
     * @return array
     */
    public function getEmpresaFromSii($rut, $dv)
    {
      if($this->isIntegrationIncomplete('sii')){
        return [false, 'Debe completar los datos para la integración con Facturación Sii.'];
      }

      return (new FacturacionSii)->busquedaReceptor($rut, $dv);
    }
}
