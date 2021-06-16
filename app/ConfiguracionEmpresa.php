<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Log, Http};
use App\User;
use App\Casts\Sii\{Account, Representante};

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
      'terminos',
      'covid19',
      'requerimientos_firmantes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'covid19' => 'boolean',
      'sii_account' => Account::class,
      'sii_representante' => Representante::class,
    ];

    /**
     * Valor por defecto de terminos
     * 
     * @var array
     */
    private $_terminos = [
      'status' => false,
      'terminos' => null,
      'users' => [],
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Configuración de Empresa';

    /**
     * Establecer la estructura para almacenar los terminos
     *
     * @param  string  $value
     * @return void
     */
    public function setTerminosAttribute($value)
    {
      $value = (array) $value;
      $terminos = $this->_terminos;
      $terminos['status'] = isset($value['status']) ? ($value['status'] == 1) : false;
      $terminos['terminos'] = $value['terminos'] ?? null;
      $terminos['users'] = $value['users'] ?? [];

      $this->attributes['terminos'] = json_encode($terminos);
    }

    /**
     * Obtener los terminos
     *
     * @param  string  $value
     * @return object
     */
    public function getTerminosAttribute($value)
    {
      $terminos = is_null($value) ? json_encode($this->_terminos) : $value;
      return json_decode($terminos);
    }

    /**
     * Establecer la estructura para almacenar los RequerimientoFirmante
     *
     * @param  string  $value
     * @return void
     */
    public function setRequerimientosFirmantesAttribute($value)
    {
      $this->attributes['requerimientos_firmantes'] = json_encode(($value ?? []), true);
    }

    /**
     * Obtener los firmantes
     *
     * @param  string  $value
     * @return object
     */
    public function getRequerimientosFirmantesAttribute($value)
    {
      return is_null($value) ? [] : json_decode($value, true);
    }

    /**
     * Obtener la Empresa a la que pertenece la Configuracion
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Evaluar si la Empresa tiene cuenta en Facturacion Sii
     *
     * @return bool
     */
    public function hasSiiAccount()
    {
      $valuesAsArray = (array) $this->sii_account;

      return !in_array(null, $valuesAsArray, true);
    }

    /**
     * Evaluar si la Empresa no tiene cuenta en Facturacion Sii
     *
     * @return bool
     */
    public function doesntHaveSiiAccount()
    {
      return !$this->hasSiiAccount();
    }

    /**
     * Evaluar si la Empresa tiene cuenta en Facturacion Sii
     *
     * @return bool
     */
    public function hasSiiRepresentante()
    {
      $valuesAsArray = (array) collect($this->sii_representante)->except('vencimiento_certificado')->toArray();

      return !in_array(null, $valuesAsArray, true);
    }

    /**
     * Evaluar si la Empresa no tiene cuenta en Facturacion Sii
     *
     * @return bool
     */
    public function doesntHaveSiiRepresentante()
    {
      return !$this->hasSiiRepresentante();
    }

    /**
     * Evaluar si los terminos y condiciones estan activos
     *
     * @param  bool  $asTag
     * @return string
     */
    public function hasActiveTerminos(bool $asTag = false)
    {
      if(!$asTag){
        return $this->terminos->status;
      }

      return $this->terminos->status ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Aceptar los terminos y condiciones
     *
     * @param  \App\User|null  $user
     * @return bool
     */
    public function acceptTerms(User $user = null)
    {
      $terminos = $this->terminos;
      $terminos->users[] = $user ? $user->id : Auth::id();
      $this->terminos = $terminos;

      return $this->save();
    }

    /**
     * Evaluar si esta activa la encuesta de Covid19
     * 
     * @param  bool  $asTag
     * @return string
     */
    public function hasActiveCovid19Encuesta(bool $asTag = false)
    {
      if(!$asTag){
        return $this->covid19;
      }

      return $this->covid19 ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Evaluar si hay firmantes configurados
     * 
     * @return bool
     */
    public function hasFirmantes()
    {
      return count($this->requerimientos_firmantes) > 0;
    }
}
