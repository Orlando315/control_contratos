<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Anticipo extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $fillable = [
    'contrato_id',
    'empleado_id',
    'fecha',
    'anticipo'
  ];

  public function setFechaAttribute($date)
  {
    $this->attributes['fecha'] = date('Y-m-d', strtotime($date));
  }

  public function getFechaAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }

  public function empleado()
  {
    return $this->belongsTo('App\Empleado');
  }

  public function anticipo()
  {
    return number_format($this->anticipo, 0, ',', '.');
  }
}
