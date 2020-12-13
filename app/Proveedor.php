<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Scopes\EmpresaScope;

class Proveedor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proveedores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'type',
      'nombre',
      'rut',
      'email',
      'telefono',
      'descripcion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
      /**
       * Eliminar toda la informacion relacionada
       */
      static::deleting(function ($model) {
        $model->contactos()->delete();
        $model->direcciones()->delete();

        if(Storage::exists($model->directory)){
          Storage::deleteDirectory($model->directory);
        }
      });
    }

    /**
     * Obtener el directorio
     */
    public function getDirectoryAttribute()
    {
      return $this->empresa->directory.'/Proveedor/'.$this->id;
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener las Direcciones
     */
    public function direcciones()
    {
      return $this->morphMany('App\Direccion', 'direccionable');
    }

    /**
     * Obtener los Contactos
     */
    public function contactos()
    {
      return $this->morphMany('App\Contacto', 'contactable');
    }

    /**
     * Obtener las Ordenes de compra
     */
    public function compras()
    {
      return $this->hasMany('App\OrdenCompra');
    }

    /**
     * Evaluar si el proveedor es Empresa
     * 
     * @return bool
     */
    public function isEmpresa()
    {
      return $this->type == 'empresa';
    }

    /**
     * Evaluar si el proveedor es Persona
     * 
     * @return bool
     */
    public function isPersona()
    {
      return !$this->isEmpresa();
    }

    /**
     * Evaluar si tiene perfil de Cliente
     * 
     * @return bool
     */
    public function hasClienteProfile()
    {
      return !is_null($this->cliente_id);
    }

    /**
     * Obtener una parte especifica del rut
     *
     * @param  int  $part
     * @return string
     */
    public function getRutPart($part)
    {
      return explode('-', $this->rut)[$part];
    }

    /**
     * Obtener el atributo formateado como label
     *
     * @return string
     */
    public function tipo()
    {
      return $this->isEmpresa() ? '<span class="label label-primary">Empresa</span>' : '<span class="label label-success">Persona</span>';
    }
}
