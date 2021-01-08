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
      return $this->hasMany('App\Transporte');
    }
}
