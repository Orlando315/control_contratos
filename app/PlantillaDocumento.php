<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class PlantillaDocumento extends Model
{
    use LogEvents;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plantillas_documentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'empleado_id',
      'postulante_id',
      'plantilla_id',
      'documento_id',
      'nombre',
      'caducidad',
      'secciones',
      'visibilidad',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'secciones' => 'array',
      'visibilidad' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'caducidad',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Documento de Plantilla';

    /**
     * Nombre base de las rutas
     * 
     * @var string
     */
    public static $baseRouteName = 'plantilla.documento';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'contrato.nombre' => 'Contrato',
      'empleado.usuario.nombreCompleto' => 'Empleado',
      'postulante.nombreCompleto' => 'Postulante',
      'plantilla.nombre' => 'Plantilla',
      'padre.nombre' => 'Documento padre',
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
     * Incluir solo los Documentos que son visibles para el Empleado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $isVisible
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $isVisible = true)
    {
      return $query->where('visibilidad', $isVisible);
    }

    /**
     * Obtener las Empresa a la que pertenece el User y esta usando en session
     *
     * @param  \App\Models\Empresa|null
     */
    public function getModelAttribute()
    {
      return $this->toEmpleado() ? $this->empleado : $this->postulante;
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener el Empleado
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Obtener la Plantilla
     */
    public function plantilla()
    {
      return $this->belongsTo('App\Plantilla');
    }

    /**
     * Obtener el Documento "padre"
     */
    public function padre()
    {
      return $this->belongsTo('App\PlantillaDocumento', 'documento_id');
    }

    /**
     * Obtener los Documento "hijos"
     */
    public function hijos()
    {
      return $this->hasMany('App\PlantillaDocumento', 'documento_id');
    }

    /**
     * Obtener el Postulante
     */
    public function postulante()
    {
      return $this->belongsTo('App\Postulante', 'postulante_id');
    }

    /**
     * Evaluar si el Documento es visible para el Empleado al que pertenece
     *
     * @param  bool  $asTag
     * @return mixed
     */
    public function isVisible($asTag = false)
    {
      if(!$asTag){
        return $this->visibilidad;
      }
      return $this->visibilidad ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Evaluar si el Documento es dirigido a un Empleado
     * 
     * @return bool
     */
    public function toEmpleado()
    {
      return !is_null($this->empleado_id);
    }

    /**
     * Evaluar si el Documento es dirigido a un Postulante
     * 
     * @return bool
     */
    public function toPostulante()
    {
      return !$this->toEmpleado();
    }

    /**
     * Asignar la fecha de caducidad en formato datetime.
     */
    public function setCaducidadAttribute($date)
    {
      $this->attributes['caducidad'] = $date ? date('Y-m-d', strtotime($date)) : null;
    }

    /**
     * Llenar las variables del contenido de la Seccion con los valores del Documento.
     *
     * @param  \App\PlantillaSeccion  $seccion
     * @return string
     */
    public function fillSeccionVariables(PlantillaSeccion $seccion)
    {
      return array_key_exists($seccion->id, $this->secciones ?? []) ? strtr($seccion->contenido, $this->fillStaticVariables($this->secciones[$seccion->id])) : $seccion->contenido;
    }

    /**
     * Llenar las variables estaticas del contenido de la Seccion con los datos del Empleado
     *
     * @param  int  $seccion
     * @return array
     */
    private function fillStaticVariables($seccion)
    {
      $staticMapped = PlantillaVariable::mappedVariablesToAttributes($this->model);
      $staticNeeded = array_intersect_key($staticMapped, $seccion);
      return collect($seccion)->merge($staticNeeded)->toArray();
    }

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults()
      ->logExcept([
        'contrato_id',
        'empleado_id',
        'postulante_id',
        'plantilla_id',
        'documento_id',
      ])
      ->logAditionalAttributes([
        'contrato.nombre',
        'empleado.usuario.nombreCompleto',
        'postulante.nombre',
        'plantilla.nombre',
        'padre.nombre',
      ]);
    }
}
