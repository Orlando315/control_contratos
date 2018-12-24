<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioEntrega extends model
{
  protected $table = 'inventarios_entregas';

  protected $fillable = [
    'cantidad',
  ];

  public function inventario()
  {
    return $this->belongsTo('App\Inventario');
  }

  /*
    Entregado a
  */
  public function entregadoA()
  {
    return $this->belongsTo('App\Usuario', 'entregado', 'id');
  }

  /*
    Realizado por
  */
  public function realizadoPor()
  {
    return $this->belongsTo('App\Usuario', 'realizado', 'id');
  }

  public function cantidad()
  {
    return number_format($this->cantidad, 0, ',', '.');
  }

  public function recibido()
  {
    return $this->recibido == 1 ? '<span class="label label-primary">Recibido</span>' : '<span class="label label-default">Pendiente</span>';
  }
}
