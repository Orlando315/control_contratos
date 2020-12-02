<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contactos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre',
      'email',
      'telefono',
      'cargo',
      'descripcion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
    ];

    /**
     * Obtener el Parent a la que pertenece
     */
    public function contactable()
    {
      return $this->morphTo();
    }

    /**
     * Obtener el tipo de parent
     *
     * @return string
     */
    public function type()
    {
      return $this->contactable_type == 'App\Cliente' ? 'cliente' : 'proveedor';
    }
}
