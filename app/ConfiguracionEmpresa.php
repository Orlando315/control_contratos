<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionEmpresa extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'configuracion_empresas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'jornada',
      'dias_vencimiento'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
