<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransporteConsumo extends Model
{
  protected $table = 'transportes_consumos';
  
  protected $fillable = [
    'contrato_id',
    'tipo',
    'fecha',
    'cantidad',
    'valor',
    'chofer',
    'observacion'
  ];

  public function setFechaAttribute($date)
  {
    $this->attributes['fecha'] = date('Y-m-d', strtotime($date));
  }

  public function transporte()
  {
    return $this->belongsTo('App\Transporte');
  }

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }

  public function tipo()
  {
    switch ($this->tipo) {
      case 1:
        return 'Mantenimiento';
        break;
      case 2:
        return 'Combustible';
        break;
      case 3:
        return 'Peaje';
        break;
      case 4:
        return 'Gastos varios';
        break;
      default:
        return 'Error';
        break;
    }
  }

  public function fecha()
  {
    return date('d-m-Y', strtotime($this->fecha));
  }

  public function cantidad()
  {
    return number_format($this->cantidad, 2, ',', '.');
  }

  public function valor()
  {
    return number_format($this->valor, 2, ',', '.');
  }

  public function adjuntos()
  {
    return $this->hasMany('App\ConsumoAdjunto', 'consumo_id');
  }
}
