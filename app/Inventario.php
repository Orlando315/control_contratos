<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Inventario extends model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }
  
  protected $fillable = [
    'empresa_id',
    'tipo',
    'nombre',
    'valor',
    'fecha',
    'cantidad',
  ];

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function entregas()
  {
    return $this->hasMany('App\InventarioEntrega');
  }

  public function tipo()
  {
    return $this->tipo == 1 ? 'Insumo' : 'EPP';
  }

  public function cantidad()
  {
    return number_format($this->cantidad, 0, ',', '.');
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
}
