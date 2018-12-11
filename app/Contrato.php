<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Contrato extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }
  
  protected $fillable = [
    'empresa_id',
    'nombre',
    'inicio',
    'fin',
    'valor'
  ];

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function empleados()
  {
    return $this->hasMany('App\Empleado');
  }

  public function documentos()
  {
    return $this->hasMany('App\Documento');
  }

  public function valor()
  {
    return number_format($this->valor, 0, ',', '.');
  }

  public function setInicioAttribute($date)
  {
    $this->attributes['inicio'] = date('Y-m-d', strtotime($date));
  }

  public function getInicioAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

  public function setFinAttribute($date)
  {
    $this->attributes['fin'] = date('Y-m-d',strtotime($date));
  }

  public function getFinAttribute($date)
  {
    return date('d-m-Y', strtotime($date));
  }

}
