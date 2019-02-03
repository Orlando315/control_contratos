<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransporteContrato extends Model
{
  protected $table = 'transportes_contratos';
  
  protected $fillable = [
    'contrato_id',
  ];

  public function transporte()
  {
    return $this->belongsTo('App\Transporte');
  }

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }
}
