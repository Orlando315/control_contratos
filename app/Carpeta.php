<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Carpeta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carpetas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'carpeta_id',
      'nombre',
    ];

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
      return $query->whereNull('carpeta_id');
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Get all of the owning carpetable models.
     */
    public function carpetable()
    {
      return $this->morphTo();
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function main()
    {
      return $this->belongsTo('App\Carpeta');
    }

    /**
     * Obtener las Carpetas
     */
    public function subcarpetas()
    {
      return $this->hasMany('App\Carpeta');
    }

    /**
     * Obtener los Documentos (Contrato/Empleado)
     */
    public function documentos()
    {
      return $this->hasMany('App\Documento');
    }

    /**
     * Obtener la url de retorno segun el modelo al que pertenece 
     */
    public function getBackUrlAttribute()
    {
      $backModel = route($this->type().'.show', ['id' => $this->carpetable_id]);
      return $this->carpeta_id ? route('carpeta.show', ['carpeta' => $this->carpeta_id]) : $backModel;
    }

    /**
     * Evaluar si la Carpeta pertenece a la clase especificada
     *
     * @param  string  $type
     * @return bool
     */
    public function isType($type)
    {
      return $this->carpetable_type == $type;
    }

    /**
     * Obtener el modelo al que pertenece la Carpeta
     *
     * @return string
     */
    public function type()
    {
      switch ($this->carpetable_type) {
        case 'App\Contrato':
          return 'contratos';
          break;
        case 'App\Empleado':
          return 'empleados';
          break;
        case 'App\TransporteConsumo':
          return 'consumos';
          break;
      }
    }
}
