<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class PlantillaSeccion extends Model
{
    use LogEvents;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plantillas_secciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'contenido', 'variables'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * Eventos que se guardaran en Logs
     * 
     * @var array
     */
    public static $recordEvents = [
      'updated',
      'deleted',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Sección de Plantilla';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'plantilla.nombre' => 'Plantilla',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::saving(function ($model) {
        $model->setVariablesFromContent();
      });
    }

    /**
     * Obtener la Plantilla a la que pertenece
     */
    public function plantilla()
    {
      return $this->belongsTo('App\Plantilla');
    }

    /**
     * Establecer las variables (PlantillaVariables) usadas em el contenido de la PlantillaSeccion
     */
    public function setVariablesFromContent()
    {
      $pattern = '/{{([a-z])+([\w]+)?}}/';
      preg_match_all($pattern, $this->contenido, $matches);

      $this->variables = PlantillaVariable::select('id','nombre', 'tipo', 'variable')->whereIn('variable', $matches[0])->get();
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
        'plantilla_id',
      ])
      ->logAditionalAttributes([
        'plantilla.nombre'
      ]);
    }
}
