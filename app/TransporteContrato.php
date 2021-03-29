<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransporteContrato extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transportes_contratos';
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
    ];

    /**
     * Obtener el Transporte al que pertenece
     */
    public function transporte()
    {
      return $this->belongsTo('App\Transporte');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }
}
