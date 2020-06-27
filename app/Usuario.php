<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use App\Scopes\EmpresaScope;

class Usuario extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'empleado_id',
      'tipo',
      'nombres',
      'apellidos',
      'rut',
      'telefono',
      'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener el atributo formateado
     *
     * @param  string  $value
     * @return string
     */
    public function getUsuarioAttribute($value)
    {
      return ucfirst($value);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Empleado del Usuario
     */
    public function empleado()
    {
      return $this->hasOne('App\Empleado', 'id', 'empleado_id');
    }

    /**
     * Obtener los Usuarios tipo Admin y Supervisor
     */
    public static function adminsYSupervisores()
    {
      return Usuario::where('tipo', 2)
                      ->orWhere('tipo', 3)->get();
    }

    /**
     * Obtener los Usuarios tipo Supervisor
     */
    public static function supervisores()
    {
      return Usuario::where('tipo', 3)->get(); 
    }

    /**
     * Obtener los Usuarios tipo Empleados
     */
    public static function empleados()
    {
      return Usuario::where('tipo', 4)->get();
    }
}
