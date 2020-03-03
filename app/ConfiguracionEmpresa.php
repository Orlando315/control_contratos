<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionEmpresa extends Model
{
  protected $table = 'configuracion_empresas';

  protected $fillable = [
    'jornada',
    'dias_vencimiento'
  ];

  public $timestamps = false;
}
