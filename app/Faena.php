<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Faena extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faenas';

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
     * Obtener los Contratos
     */
    public function contratos()
    {
      return $this->hasMany('App\Contrato');
    }

    /**
     * Obtener los Transportes
     */
    public function transportes()
    {
      return $this->belongsToMany('App\Transporte', 'transportes_faenas', 'faena_id', 'transporte_id')->withTimestamps();
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

    /**
     * Obtener las Facturas
     */
    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function asTag()
    {
      return '<small class="label label-default">'.$this->nombre.'</small>';
    }
}
