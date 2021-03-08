<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Auth;

class RequerimientoMaterial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requerimientos_materiales';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'solicitante',
      'contrato_id',
      'faena_id',
      'centro_costo_id',
      'dirigido',
      'status',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el User del Solicitante
     */
    public function userSolicitante()
    {
      return $this->belongsTo('App\User', 'solicitante');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener la Faena
     */
    public function faena()
    {
      return $this->belongsTo('App\Faena');
    }

    /**
     * Obtener el CentroCosto
     */
    public function centroCosto()
    {
      return $this->belongsTo('App\CentroCosto');
    }

    /**
     * Obtener el User de a quien va dirigido
     */
    public function dirigidoA()
    {
      return $this->belongsTo('App\User', 'dirigido');
    }

    /**
     * Obtener los Productos del RequerimientoMaterial
     */
    public function productos()
    {
      return $this->hasMany('App\RequerimientoMaterialProducto', 'requerimiento_id');
    }

    /**
     * Obtener los User que son firmantes del RequerimientoMaterial
     */
    public function firmantes()
    {
      return $this->belongsToMany('App\User', 'requerimientos_materiales_firmantes', 'requerimiento_id', 'user_id')
      ->using('App\RequerimientoMaterialFirmante')
      ->withPivot('texto', 'obligatorio', 'status');
    }

    /**
     * Obtener los firmantes del RequerimientoMaterial
     */
    public function requerimientoFirmante()
    {
      return $this->hasMany('App\RequerimientoMaterialFirmante', 'requerimiento_id');
    }

    /**
     * Obtener el RequerimientoMaterialFirmante del User en sesion
     */
    public function sessionFirmante()
    {
      return $this->hasOne('App\RequerimientoMaterialFirmante', 'requerimiento_id')->where('user_id', Auth::id());
    }

    /**
     * Evaluar si el Requerimiento esta pendiente de Aprobar/Rechazar
     * 
     * @return bool
     */
    public function isPendiente()
    {
      return is_null($this->status);
    }

    /**
     * Obtener el atributo formateado
     *
     * @param  bool  $asText
     * @return string
     */
    public function status($asText = false)
    {
      if(is_null($this->status)){
        return '<span class="label label-default">Pendiente</span>';
      }

      $status = $this->status ? '<span class="label label-primary">Aprobado</span>' : '<span class="label label-danger">Rechazado</span>';

      return $asText ? strip_tags($status) : $status;
    }

    /**
     * Crear la relacion con los User firmantes
     * 
     * @return void
     */
    public function createFirmantes()
    {
      $firmantes = $this->empresa->configuracion->requerimientos_firmantes;
      $firmantesInfo = [];
      foreach($firmantes as $firmante){
        $firmantesInfo[$firmante['usuario']] = collect($firmante)->only('texto', 'obligatorio')->toArray();
      }

      $this->firmantes()->attach($firmantesInfo);
    }

    /**
     * Evaluar si el User en sesion debe aprobar el Requerimiento
     * 
     * @return bool
     */
    public function userNeedsToApprove()
    {
      if($this->firmantes->contains(Auth::user())){
        return $this->sessionFirmante->isPendiente();
      }

      return false;
    }

    /**
     * Evaluar si el Requerimiento debe ser Aprobado
     * 
     * @return bool
     */
    private function shouldBeApproveb()
    {
      $totalFirmas = $this->requerimientoFirmante()->count();
      $firmasAprobadas = $this->requerimientoFirmante()->aprobado()->count();
      $firmasObligatorias = $this->requerimientoFirmante()->obligatorio()->count();
      $firmasObligatoriasAprobadas = $this->requerimientoFirmante()->obligatorio()->aprobado()->count();

      if(($totalFirmas == $firmasAprobadas) || ($firmasObligatorias == $firmasObligatoriasAprobadas)){
        return true;
      }

      return false;
    }

    /**
     * Evaluar si el Requerimiento debe ser Rechazado
     * 
     * @return bool
     */
    private function shouldBeRejected()
    {
      return $this->requerimientoFirmante()->obligatorio()->rechazado()->exists();
    }

    /**
     * Actualizar el status del Requerimiento en base al status de las firmas
     * 
     * @return void
     */
    public function updateStatus()
    {
      if(!$this->isPendiente()){
        return false;
      }
      
      if($this->shouldBeApproveb()){
        $this->status = true;
        $this->save();
      }

      if($this->shouldBeRejected()){
        $this->status = false;
        $this->save();
      }
    }
}
