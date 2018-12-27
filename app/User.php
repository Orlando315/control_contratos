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

  public function checkRole($role)
  {
    return $this->tipo <= $role;
  }

  public function tipo()
  {
    switch ($this->tipo) {
      case 1:
        $tipo = 'Empresa';
        break;
      case 2:
        $tipo = 'Administrador';
        break;
      case 3:
        $tipo = 'Supervisor';
        break;
      default:
        $tipo = 'Empleado';
        break;
    }

    return $tipo;
  }
}
