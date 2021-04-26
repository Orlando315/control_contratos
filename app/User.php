<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use App\Notifications\ResetPassword;
use Laratrust\Traits\LaratrustUserTrait;
use Laratrust\Helper;
use App\Role;

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
      'empleado_id',
      'nombres',
      'apellidos',
      'rut',
      'telefono',
      'email',
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
     * Empresa a la que pertenece el User y esta usando en session
     *
     * @var array
     */
    private $_empresa = null;

    /**
     * Filtrar por los usuarios con Roles de administracion
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStaff($query)
    {
      return $query->whereInAllRolesIs('empresa|administrador|supervisor');
    }

    /**
     * Filtrar por los usuarios con Role de Supervisor
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSupervisores($query)
    {
      return $query->whereInAllRolesIs('supervisor');
    }

    /**
     * Filtrar por los usuarios con Roles de administracion
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmpleados($query)
    {
      return $query->whereInAllRolesIs('empleado');
    }

    /**
     * Filtrar por los usuarios con el/los Roles proporcionados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param   array|string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereInAllRolesIs($query, $role)
    {
      $role = Arr::wrap(Helper::standardize($role));

      return $query->whereHas('allRoles', function ($roleQuery) use ($role) {
        $roleQuery->whereIn('name', $role);
      });
    }

    /**
     * Obtener las Empresa a la que pertenece el User y esta usando en session
     *
     * @param  \App\Models\Empresa|null
     */
    public function getEmpresaAttribute()
    {
      return $this->_empresa = $this->_empresa ?? $this->empresas()->first();
    }

    /**
     * Obtener las Empresas a la que pertenece el User
     */
    public function empresas()
    {
      return $this->belongsToMany('App\Empresa', 'empresa_user');
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
    * Obtener el Role inactivo del User
    *
    * @return  \App\Models\Role|null
    */
    public function inactiveRole()
    {
      return $this->roles(false)->first();
    }

    /**
     * Relacion con Roles segun el status proporcionado
     *
     * @param  bool  $activeRoles
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function roles($activeRoles = true)
    {
      return $this->morphToMany(
        'App\Role',
        'user',
        'role_user',
        'user_id',
        'role_id'
      )
      ->when(!is_null($activeRoles), function (Builder $query) use ($activeRoles) {
        $query->where('active', $activeRoles);
      })
      ->withPivot('active');
    }

    /**
     * Obtener todos los Roles (activos e inactivos)
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function allRoles()
    {
      return $this->roles(null);
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
     * Obtener las respuestas de la encuesta Covid19
     */
    public function covid19Respuestas()
    {
      return $this->hasMany('App\Covid19Respuesta');
    }

    /**
     * Obtener los RequerimientoMaterial solicitados por el User
     */
    public function requerimientosMateriales()
    {
      return $this->hasMany('App\RequerimientoMaterial', 'solicitante');
    }

    /**
     * Obtener los RequerimientoMaterial dirigidos al User
     */
    public function requerimientosMaterialesDirigido()
    {
      return $this->hasMany('App\RequerimientoMaterial', 'dirigido');
    }

    /**
     * Obtener los RequerimientoMaterial donde el User es firmante
     */
    public function requerimientosMaterialesFirmante()
    {
      return $this->hasMany('App\RequerimientoMaterialFirmante', 'user_id');
    }

    /**
     * Obtener los Egresos de Inventario
     */
    public function egresos()
    {
      return $this->hasMany('App\InventarioV2Egreso');
    }

    /**
     * Evaluar si el User es tiene algun role de Administrador
     * 
     * @return bool
     */
    public function isSuper()
    {
      return $this->hasRole('developer|superadmin');
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
     * Evaluar si el User tiene algun role inactivo
     * 
     * @param  string  $name
     * @return bool
     */
    public function hasInactiveRole($name)
    {
      $roles = Arr::wrap(Helper::standardize($name));

      $rolesCount = $this->roles(false)
      ->whereIn('name', $roles)
      ->count();

      return $rolesCount > 0;
    }

    /**
     * Evaluar si el User tiene algun role inactivo
     * 
     * @param  string  $name
     * @return bool
     */
    public function hasActiveOrInactiveRole($name)
    {
      $roles = Arr::wrap(Helper::standardize($name));

      $rolesCount = $this->roles(null)
      ->whereIn('name', $roles)
      ->count();

      return $rolesCount > 0;
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

    /**
     * Obtener los nombre de los roles asignados al User
     *
     * @param  bool  $asTag
     * @return string
     */
    public function allRolesNames($asTag = true)
    {
      $names = $this->roles(null)
      ->get()
      ->transform(function ($role) use ($asTag) {
        return $asTag ? $role->asTag() : $role->name();
      })
      ->toArray();

      $separator = $asTag ? ' ' : ', ';

      return implode($separator, $names);
    }

    /**
     * Asignar role al User
     * 
     * @param  \App\Role  $role
     */
    public function assignRole(Role $role)
    {
      // Los Empleados siempre deben tener el Role empleado
      $roleEmpleado = Role::firstWhere('name', 'empleado');
      $rolesToSync = [$roleEmpleado->id];

      if($this->isEmpleado()){
        // Si el role a asignar no es el de empleado, se agrega como inactivo
        // Si el role enviado es empleado, no es necesario agregarlo porque ya debe estar agregado
        if($role->name != 'empleado'){
          $rolesToSync[$role->id] = ['active' => false];
        }
        // Se sincronizan los roles
        $this->roles(null)->sync($rolesToSync);
        // Si no existe un role activo, se activa el de empleado
        if(!$this->role()){
          $this->roles(null)->updateExistingPivot($roleEmpleado->id, ['active' => true]);
        }
        // Eliminar cache de roles/permissions del User
        $this->flushCache();
      }
      // Los usuarios que no son empleados solo pueden tener 1 role
      else{
        $this->syncRoles([$role->id]);
      }
    }

    /**
     * Eliminar role Empleado
     */
    public function removeRoleEmpleado()
    {
      if($this->hasActiveOrInactiveRole('empleado')){
        $roleEmpleado = Role::firstWhere('name', 'empleado');

        $this->roles(null)->detach($roleEmpleado->id);
        // Si no existe un role activo, se activa el primero que se encuentre
        if(!$this->role()){
          $role = $this->roles(null)->first();
          $this->roles(null)->updateExistingPivot($role->id, ['active' => true]);
        }
        // Eliminar cache de roles/permissions del User
        $this->flushCache();
      }
    }

    /**
     * Evaluar si el User ha aceptado los terminos y condiciones de la Empresa
     * 
     * @return bool
     */
    public function haventAcceptedTerms()
    {
      $users = $this->empresa->configuracion->terminos->users;

      return !in_array($this->id, $users);
    }

    /**
     * Evaluar si el User ya ha respondido la encuesta Covid19 "hoy"
     * 
     * @return bool
     */
    public function haventAnsweredCovid19Today()
    {
      return !$this->covid19Respuestas()->whereDate('created_at', date('Y-m-d'))->exists();
    }
}
