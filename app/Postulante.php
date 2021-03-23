<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'postulantes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empleado_id',
      'nombres',
      'apellidos',
      'rut',
      'telefono',
      'email',
    ];

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el nombre completo del Usuario
     * 
     * @return string
     */
    public function nombre()
    {
      return $this->nombres.' '.$this->apellidos;
    }

}
