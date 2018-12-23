<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Transporte extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $fillable = [
    'vehiculo',
    'patente',
  ];

  public function usuario()
  {
    return $this->belongsTo('App\Usuario', 'user_id');
  }

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }

  public function consumos()
  {
    return $this->hasMany('App\TransporteConsumo');
  }
}
