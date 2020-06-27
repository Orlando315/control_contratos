<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Transporte extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transportes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'vehiculo',
      'patente',
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
     * Obtener el Usuario
     */
    public function usuario()
    {
      return $this->belongsTo('App\Usuario', 'user_id');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contratos()
    {
      return $this->hasMany('App\TransporteContrato');
    }

    /**
     * Obtener lso Consumos
     */
    public function consumos()
    {
      return $this->hasMany('App\TransporteConsumo');
    }
}
