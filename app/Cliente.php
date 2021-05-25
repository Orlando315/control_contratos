<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Cliente extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clientes';

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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'type' => 'Tipo',
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
      return $this->empresa->directory.'/Cliente/'.$this->id;
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
     * Obtener Proveedor
     */
    public function proveedor()
    {
      return $this->belongsTo('App\Proveedor');
    }

    /**
     * Obtener Cotizaciones
     */
    public function cotizaciones()
    {
      return $this->hasMany('App\Cotizacion');
    }

    /**
     * Obtener las Facturaciones de las Cotizaciones
     */
    public function facturaciones()
    {
      return $this->hasManyThrough('App\Facturacion', 'App\Cotizacion');
    }

    /**
     * Evaluar si el Cliente es Empresa
     * 
     * @return bool
     */
    public function isEmpresa()
    {
      return $this->type == 'empresa';
    }

    /**
     * Evaluar si el Cliente es Persona
     * 
     * @return bool
     */
    public function isPersona()
    {
      return !$this->isEmpresa();
    }

    /**
     * Evaluar si tiene perfil de Proveedor
     * 
     * @return bool
     */
    public function hasProveedorProfile()
    {
      return !is_null($this->proveedor_id);
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
     * Obtener solo el rut
     * 
     * @return string
     */
    public function getRut()
    {
      return $this->getRutPart(0);
    }

    /**
     * Obtener el digito validador del rut
     * 
     * @return string
     */
    public function getRutDv()
    {
      return $this->getRutPart(1);
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

    /**
     * Opciones para personalizar los Log
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults();
    }
}
