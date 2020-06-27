<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    /*
     * Utilizado solo para autenticacion
     */
    use Notifiable;

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
     * Obtener las Entregas de Inventario
     */
    public function entregasPendientes()
    {
      return $this->hasMany('App\InventarioEntrega', 'entregado')
                  ->select(['id', 'inventario_id', 'cantidad', 'created_at'])
                  ->where('recibido', 0)
                  ->with('inventario:id,nombre');
    }

    /**
     * Obtener los Sueldos
     *
     * @param  bool  $pendiente
     */
    public function sueldos($pendiente = false)
    {
      return $this->hasMany('App\EmpleadosSueldo', 'empleado_id', 'empleado_id')
                  ->when($pendiente, function($query){
                    $query->where('recibido', false);
                  });
    }

    /**
     * Verificar si el role especificado
     *
     * @param  int  $role
     * @return bool
     */
    public function checkRole($role)
    {
      return $this->tipo <= $role;
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
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

    /**
     * Enviar correo de recuperacion de password al Usuario
     */
    public function sendPasswordResetNotification($token)
    {
      $this->notify(new ResetPassword($token));
    }
}
