<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
  /*
    Utilizado solo para autenticacion
  */

  use Notifiable;

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
        $tipo = 'Super Administrador';
        break;
      case 2:
        $tipo = 'Empresa';
        break;
      case 3:
        $tipo = 'Administrador';
        break;
      case 4:
        $tipo = 'Supervisor';
        break;
      default:
        $tipo = 'Empleado';
        break;
    }

    return $tipo;
  }

  public function empleado()
  {
    return $this->hasOne('App\Empleado', 'id', 'empleado_id');
  }

  public function sueldos($pendiente = false)
  {
    return $this->hasMany('App\EmpleadosSueldo', 'empleado_id', 'empleado_id')
                  ->when($pendiente, function($query){
                    $query->where('recibido', false);
                  });
  }

  public function sendPasswordResetNotification($token)
  {
      $this->notify(new ResetPassword($token));
  }
  
  public function encuestas()
  {
    return $this->hasMany('App\Encuesta');
  }

  public function preguntas()
  {
    return $this->hasMany('App\EncuestaPregunta');
  }

  public function respuestas()
  {
    return $this->hasMany('App\EncuestaRespuesta');
  }

  public function ayudas()
  {
    return $this->hasMany('App\Ayuda');
  }

  public function encuestasPendientes()
  {
    return Encuesta::whereDoesntHave('respuestas', function ($query) {
      $query->where('user_id', $this->id);
    });
  }
}
