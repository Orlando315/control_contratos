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
    return $this->tipo == 1 ? 'Mantenimiento' : 'Combustible';
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

  public function adjunto()
  {

    return $this->adjunto ? '<a href="' . $this->getDownloadLink() . '">Descargar</a>' : 'N/A';
  }

  protected function getDownloadLink()
  {
    return route('consumos.download', ['id' => $this->id]);
  }
}
