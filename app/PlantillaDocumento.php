<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class PlantillaDocumento extends Model
{
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
      'plantilla_id',
      'documento_id',
      'nombre',
      'caducidad',
      'secciones',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'secciones' => 'array',
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
      $staticMapped = PlantillaVariable::mappedVariablesToAttributes($this->empleado);
      $staticNeeded = array_intersect_key($staticMapped, $seccion);
      return collect($seccion)->merge($staticNeeded)->toArray();
    }

}
