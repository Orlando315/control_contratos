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
}
