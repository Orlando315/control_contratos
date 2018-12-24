<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  /*
    Utilizado solo para autenticacion
  */

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function entregasPendientes()
  {
    return $this->hasMany('App\InventarioEntrega', 'entregado')
                ->select(['id', 'inventario_id', 'cantidad', 'created_at'])
                ->where('recibido', 0)
                ->with('inventario:id,nombre');
  }
}
