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
    protected $fillable = ['empresa_id', 'nombre', 'type'];

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
     * Obtener el status
     * 
     * @return bool
     */
    public function status()
    {
      return !is_null($this->documento);
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
