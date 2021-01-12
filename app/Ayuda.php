<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ayuda extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ayudas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'titulo',
      'video',
      'contenido',
      'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'status' => 'boolean',
    ];

    /**
     * Scope a query to only include active ayudas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
      return $query->where('status', true);
    }

    /**
     * Obtener los Roles
     */
    public function roles()
    {
      return $this->belongsToMany('App\Role', 'ayuda_role');
    }

    /**
     * Evaluar si la ayuda esta activa
     * 
     * @return bool
     */
    public function isActive(){
      return $this->status;
    }

    /**
     * Evaluar si la ayuda tiene video
     * 
     * @return bool
     */
    public function hasVideo(){
      return !is_null($this->video);
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function status()
    {
      return $this->isActive() ? '<span class="label label-primary">Activo</span>' : '<span class="label label-success">Inactivo</span>';
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function video()
    {
      return $this->hasVideo() ? '<span class="label label-primary">Sí</span>' : '<span class="label label-deaulf">No</span>';
    }

    /**
     * Obtener los nombre de los roles asignados al User
     *
     * @param  bool  $asTag
     * @return string
     */
    public function allRolesNames($asTag = true)
    {
      $names = $this->roles()
      ->get()
      ->transform(function ($role) use ($asTag) {
        return $asTag ? $role->asTag() : $role->name();
      })
      ->toArray();

      $separator = $asTag ? ' ' : ', ';

      return implode($separator, $names);
    }
}
