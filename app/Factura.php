<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Factura extends Model
{
  
  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $fillable = [
    'contrato_id',
    'etiqueta_id',
    'tipo',
    'nombre',
    'realizada_para',
    'realizada_por',
    'fecha',
    'valor',
    'pago_fecha',
    'pago_estado',
  ];

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }

  public function etiqueta()
  {
    return $this->belongsTo('App\Etiqueta');
  }

  public function tipo()
  {
    return $this->tipo == 1 ? 'Ingreso' : 'Egreso';
  }

  public function setFechaAttribute($date)
  {
    $this->attributes['fecha'] = date('Y-m-d',strtotime($date));
  }

  public function getFechaAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function valor()
  {
    return number_format($this->valor, 0, ',', '.');
  }

  public function setPagoFechaAttribute($date)
  {
    $this->attributes['pago_fecha'] = date('Y-m-d',strtotime($date));
  }

  public function getPagoFechaAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function pago()
  {
    return $this->pago_estado == 1 ? '<span class="label label-success">Pagada</span>' : '<span class="label label-default">Pendiente</span>';
  }

  public function directory()
  {
    return 'Empresa' . $this->empresa_id . '/Facturas/' . $this->id;
  }

  public function adjunto($adjunto)
  {

    return $this->{"adjunto{$adjunto}"} ? '<a href="' . $this->getDownloadLink($adjunto) . '">Descargar</a>' : 'N/A';
  }

  protected function getDownloadLink($adjunto)
  {
    return route('facturas.download', ['factura' => $this->id, 'adjunto' => $adjunto]);
  }

}
