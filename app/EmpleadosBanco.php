<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmpleadosBanco extends Model
{
  //
  protected $table = 'empleados_bancos';

  protected $fillable = [
    'nombre',
    'tipo_cuenta',
    'cuenta'
  ];

  public $timestamps = false;
}
