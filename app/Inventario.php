<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
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
    'cantidad'
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
    switch ($this->tipo) {
      case 1:
        $tipo = 'Insumo';
        break;
      case 2:
        $tipo = 'EPP';
        break;
      case 3:
      default:
        $tipo = 'Otro';
        break;
    }

    return $tipo;
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

  public function adjunto()
  {

    return $this->adjunto ? '<a href="' . $this->getDownloadLink() . '">Descargar</a>' : 'N/A';
  }

  protected function getDownloadLink()
  {
    return route('inventarios.download', ['id' => $this->id]);
  }

  public function directory()
  {
    return 'Empresa' . Auth::user()->empresa_id . '/Inventarios/' . $this->id;
  }
}
