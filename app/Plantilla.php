<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Plantilla extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plantillas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'status'];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Plantilla de Documento';

    /**
     * Nombre base de las rutas
     * 
     * @var string
     */
    public static $baseRouteName = 'plantilla';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'status' => 'Estatus',
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
     * Obtener las Secciones
     */
    public function secciones()
    {
      return $this->hasMany('App\PlantillaSeccion');
    }

    /**
     * Obtener los Documentos
     */
    public function documentos()
    {
      return $this->hasMany('App\PlantillaDocumento');
    }

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults();
    }
}
