<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\User;
use App\Scopes\EmpresaScope;

class Usuario extends User
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $table = 'users';
  
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

  protected $hidden = [
    'password'
  ];

  /*
    Todos los usuarios de tipo Administrator y Supervisor
  */
  public static function adminsYSupervisores()
  {
    return Usuario::where('tipo', 2)
                    ->orWhere('tipo', 3)->get();
  }

  /*
    Todos los usuarios de tipo Supervisor
  */
  public static function supervisores()
  {
    return Usuario::where('tipo', 3)->get(); 
  }

  /*
    Todos los usuarios de tipo Supervisor
  */
  public static function empleados()
  {
    return Usuario::where('tipo', 4)->get();
  }

  public function getUsuarioAttribute($usuario)
  {
    return ucfirst($usuario);
  }

  public function empleado()
  {
    return $this->belongsTo('App\Empleado');
  }

  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }
}
