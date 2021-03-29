<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Requisito extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requisitos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['empresa_id', 'nombre', 'type', 'folder'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'folder' => 'boolean',
    ];

    /**
     * Tipos de Requisitos permitidos.
     *
     * @var array
     */
    private static $allowedTypes = ['contratos', 'empleados', 'transportes'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Scope a query to only include active coupons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
      return $query->where('type', self::allowedTypes($type));
    }

    /**
     * Scope a query to only include folders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFolder($query)
    {
      return $query->where('folder', true);
    }

    /**
     * Scope a query to only include files.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFile($query)
    {
      return $query->where('folder', false);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Evaluar si el Requisito es una carpeta
     * 
     * @return bool
     */
    public function isFolder()
    {
      return $this->folder;
    }

    /**
     * Evaluar si el Requisito es un archivo
     * 
     * @return bool
     */
    public function isFile()
    {
      return !$this->isFolder();
    }

    /**
     * Obtener el status
     * 
     * @return bool
     */
    public function status()
    {
      return !is_null($this->documento);
    }

    /**
     * Obtener el icono del requisito si es carpeta o archivo
     * 
     * @return string
     */
    public function icon()
    {
      $icon = $this->isFolder() ? 'folder-o' : 'file-o';
      return '<i class="fa fa-'.$icon.'"></i>';
    }

    /**
     * Comparar el tipo especificado con los permitidios, o devolver uno por defecto si n es permitido.
     * Si no se especifica un tipo, se retornan todos los tipos permitidos.
     *
     * @param  string  $string
     * @return mixed string|array
     */
    public static function allowedTypes($type = null)
    {
      return $type ? (in_array($type, self::$allowedTypes) ? $type : 'contratos') : self::$allowedTypes;
    }
}
