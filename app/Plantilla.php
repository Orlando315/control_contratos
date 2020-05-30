<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Plantilla extends Model
{
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
}
