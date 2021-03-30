<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class CentroCosto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'centro_costos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre'
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
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener los Inventarios V2 - Egreso
     */
    public function inventariosV2Egreso()
    {
      return $this->hasMany('App\InventarioV2Egreso');
    }

    /**
     * Obtener los RequerimientoMaterial
     */
    public function requerimientosMateriales()
    {
      return $this->hasMany('App\RequerimientoMaterial');
    }
}