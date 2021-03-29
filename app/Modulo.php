<?php

namespace App;

use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Support\Facades\Auth;

class Modulo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'modulos';

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
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
      'permissions'
    ];

    /**
     * Modulos que pueden ser accedidos por usuarios (Que no sean superadmins)
     *
     * @var array
     */
    private static $_admins = [
      'development',
      'superadmin',
      'users',
      'contratos',
      'faena',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      if(Auth::check() && !Auth::user()->hasRole('developer') && !Auth::user()->hasRole('developer')){
        static::addGlobalScope('notSuper', function (Builder $builder) {
            $builder->whereNotIn('name', self::$_admins);
        });
      }
    }

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
     * Obtener los Permission
     */
    public function permissions()
    {
      return $this->hasMany('App\Permission');
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
}
