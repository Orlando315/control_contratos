<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

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
     * Filtrar por los usuarios con Roles de administracion
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStaff($query)
    {
      return $query->whereRoleIs(['administrador', 'supervisor']);
    }

    /**
     * Filtrar por los usuarios con Roles de administracion
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSupervisores($query)
    {
      return $query->whereRoleIs(['supervisor']);
    }

    /**
     * Filtrar por los usuarios con Roles de administracion
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmpleados($query)
    {
      return $query->whereRoleIs(['empleado']);
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
    * Obtener el Role del User
    *
    * @return  \App\Models\Role|null
    */
    public function role()
    {
      return $this->roles()->first();
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
      return $this->hasRole('empresa');
    }

    /**
     * Verificar si el uer puede acceder al area Admin
     *
     * @return bool
     */
    public function isStaff()
    {
      return !$this->hasRole('empleado');
    }

    /**
     * Verificar si el user tiene role administrador
     *
     * @return bool
     */
    public function isAdministrador()
    {
      return $this->hasRole('administrador');
    }

    /**
     * Evaluar si el User es tiene algun role de Administrador
     * 
     * @return bool
     */
    public function isAdmin()
    {
      return $this->hasRole('developer|superadmin|empresa|administrador');
    }

    /**
     * Evaluar si el User no tiene algun role de Administrador
     * 
     * @return bool
     */
    public function isNotAdmin()
    {
      return !$this->isAdmin();
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
     * Check if user has a permission by its name.
     *
     * @param  string|array  $permission Permission string or array of permissions.
     * @param  string|bool  $team      Team name or requiredAll roles.
     * @param  bool  $requireAll All permissions in the array are required.
     * @return bool
     */
    public function hasPermission($permission, $team = null, $requireAll = false)
    {
      if($this->hasRole('developer')){
        return true;
      }

      if(!is_array($permission) && $permission != 'god' && $this->hasRole('superadmin')){
        return true;
      }

      return $this->laratrustUserChecker()->currentUserHasPermission(
        $permission,
        $team,
        $requireAll
      );
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function tipo()
    {
      return $this->role()->name();
    }

    /**
     * Enviar correo de recuperacion de password al Usuario
     */
    public function sendPasswordResetNotification($token)
    {
      $this->notify(new ResetPassword($token));
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
