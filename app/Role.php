<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Laratrust\Models\LaratrustRole;
use Illuminate\Support\Facades\Auth;

class Role extends LaratrustRole
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'display_name',
      'description',
    ];

    /**
     * Roles developers
     *
     * @var array
     */
    private static $_developers = [
      'developer',
    ];

    /**
     * Roles superadministradores
     *
     * @var array
     */
    private static $_superadmins = [
      'superadmin',
    ];

    /**
     * Roles de users (No superadmins) en el sistema
     *
     * @var array
     */
    private static $_users = [
      'empresa',
      'administrador',
      'supervisor',
      'empleado',
    ];

    /**
     * Roles de users (No supermdmins, ni Empresa) en el sistema
     *
     * @var array
     */
    private static $_simple = [
      'administrador',
      'supervisor',
      'empleado'
    ];

    /**
     * Establecer el atributo formateado.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
      $this->attributes['name'] = strtolower($value);
    }
     /**
     * Obtener los Roles que no sean superadmins
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAdmins(Builder $query)
    {
      return $query->whereIn('name', self::$_simple);
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function name()
    {
      return ucfirst($this->display_name ?? $this->name);
    }

    /**
     * Obtener el name de los Roles del tipo especificado
     * 
     * @param  string  $type
     * @return array
     */
    public static function getRoles($type)
    {
      $rolesType = "_${type}";
      $roles = self::$$rolesType ?? [];

      if(Auth::user()->hasRole('developer')){
        $roles = array_merge(self::$_developers, $roles);
      }

      return $roles;
    }
}
