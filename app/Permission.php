<?php

namespace App;

use Laratrust\Models\LaratrustPermission;
use Illuminate\Support\Facades\Auth;

class Permission extends LaratrustPermission
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
      'display_name',
      'description',
      'modulo_id',
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
     * Obtener el modelo al que pertenece
     */
    public function modulo()
    {
      return $this->belongsTo('App\Modulo');
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
     * CRUD de permisos para el modulo especificado
     *
     * @param  string  $name
     * @return array
     */
    public static function createCrud($name)
    {
      $display = ucfirst($name);
      return [
              [
                'name' => $name.'-index',
                'display_name' => 'Listar '.$name.'s',
              ],
              [
                'name' => $name.'-view',
                'display_name' => 'Ver '.$name,
              ],
              [
                'name' => $name.'-create',
                'display_name' => 'Crear '.$name,
              ],
              [
                'name' => $name.'-edit',
                'display_name' => 'Editar '.$name,
              ],
              [
                'name' => $name.'-delete',
                'display_name' => 'Eliminar '.$name,
              ],
            ];
    }
}
