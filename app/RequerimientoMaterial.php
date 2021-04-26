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
      'fecha',
      'urgencia',
      'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'fecha',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'status' => 'boolean',
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
     * Establecer la urgencia
     *
     * @param  string  $value
     * @return void
     */
    public function setUrgenciaAttribute($value)
    {
      $this->attributes['urgencia'] = ($value != 'normal' && $value != 'urgente') ? 'normal' : $value;
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
      ->withPivot('texto', 'obligatorio', 'observacion', 'status');
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
     * Obtener los logs
     */
    public function logs()
    {
      return $this->hasMany('App\RequerimientoMaterialLog', 'requerimiento_id');
    }

    /**
     * Obtener los Ordenes de compra
     */
    public function compras()
    {
      return $this->hasMany('App\OrdenCompra', 'requerimiento_id');
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
     * Evaluar si el Requerimiento fue Aprobado
     * 
     * @return bool
     */
    public function isAprobado()
    {
      return $this->status == true;
    }

    /**
     * Evaluar si el Requerimiento es urgente
     * 
     * @return bool
     */
    public function isUrgente()
    {
      return $this->urgencia == 'urgente';
    }

    /**
     * Evaluar si el Requerimiento tiene Compras
     * 
     * @return boolean
     */
    public function hasCompras()
    {
      return $this->compras()->count() > 1;
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function id()
    {
      return 'RM - '.$this->id;
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
     * Obtener el atributo formateado
     *
     * @param  bool  $asText
     * @return string
     */
    public function urgencia($asText = false)
    {
      $urgencia = $this->isUrgente() ? '<span class="label label-danger">Urgente</span>' : '<span class="label label-default">Normal</span>';

      return $asText ? strip_tags($urgencia) : $urgencia;
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
     * Evaluar si el User en sesion es firmante
     * 
     * @return bool
     */
    public function userIsFirmante()
    {
      return $this->firmantes->contains(Auth::user());
    }

    /**
     * Evaluar si el User en sesion debe aprobar el Requerimiento
     * 
     * @return bool
     */
    public function userNeedsToApprove()
    {
      if($this->userIsFirmante()){
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

    /**
     * Guardar los cambios porporcionados en un log
     * 
     * @param  string  $action
     * @param  array|\App\RequerimientoMaterialProducto|\App\RequerimientoMaterial  $data
     * @return void
     */
    public function logAction($action, $data)
    {
      $type = $this->userIsFirmante() ? 'firmante' : (Auth::id() == $this->solicitante ? 'solicitante' : 'admin');
      $logs = [];

      $message = 'El usuario <strong>'.Auth::user()->nombre().'</strong> ';

      if($action == 'delete'){
        $logs[] = [
          'type' => $type,
          'message' => $message.'ha eliminado el producto <strong>'.$data->nombre.'</strong>. Cantidad: <strong>'.$data->cantidad().'<strong>',
        ];
      }

      if($action == 'add'){
        foreach ($data as $producto){
          $logs[] = [
            'type' => $type,
            'message' => $message.'ha agregado el producto <strong>'.$producto['nombre'].'</strong>. Cantidad: <strong>'.$producto['cantidad'].'<strong>',
          ];
        }
      }

      if($action == 'update' && $this->wasChanged()){
        $updates = collect($this->getChanges())
        ->except('updated_at')
        ->toArray();
        $changes = [];

        foreach ($updates as $key => $val) {
          [$title, $from, $to] = $this->getChangedValues($key, $data);

          $changes[] = '<strong>'.$title.':</strong> '.$from.' -> '.$to;
        }

        $logs[] = [
          'type' => $type,
          'message' => $message.'actualizado la información: '.join(', ', $changes),
        ];
      }

      $this->logs()->createMany($logs);
    }

    /**
     * Obtener los valores cambiados segun elatributo proporcionado
     * 
     * @param  string  $key
     * @param  \App\RequerimientoMaterial  $data
     * @return array
     */
    public function getChangedValues($key, $data)
    {
      switch ($key) {
        case 'contrato_id':
          return ['Contrato', $data->contrato->nombre, $this->contrato->nombre];
        break;
        case 'faena_id':
          return ['Faena', optional($data->faena)->nombre ?? 'N/A', optional($this->faena)->nombre ?? 'N/A'];
        break;
        case 'centro_costo_id':
          return ['Centro de costo', optional($data->centroCosto)->nombre ?? 'N/A', optional($this->centroCosto)->nombre ?? 'N/A'];
        break;
        case 'dirigido':
          return ['Dirigido a', $data->dirigidoA->nombre(), $this->dirigidoA->nombre()];
        break;
        case 'fecha':
          return ['Requerido para', optional($data->fecha)->format('d-m-Y') ?? 'N/A', optional($this->fecha)->format('d-m-Y') ?? 'N/A'];
        break;
        case 'urgencia':
          return ['Urgencia', $data->urgencia, $this->urgencia];
        break;
      }
    }
}
