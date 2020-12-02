<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * Verificar si el User es Empresa
     * 
     * @return boolean
     */
    public function isEmpresa()
    {
      return $this->tipo == 1;
    }

    /**
     * Verificar si el uer puede acceder al area Admin
     *
     * @return bool
     */
    public function isStaff()
    {
      return $this->tipo < 4;
    }

    /**
     * Evaluar si el Usuario es Administrador (Tipo 1 o 2)
     * 
     * @return bool
     */
    public function isAdmin()
    {
      return $this->tipo < 3;
    }

    /**
     * Evaluar si el Usuario es Empleado
     * 
     * @return bool
     */
    public function isEmpleado()
    {
      return !is_null($this->empleado);
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
