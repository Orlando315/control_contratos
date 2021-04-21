<?php

namespace App;

use Illuminate\Database\Eloquent\{Model, Builder};
use App\Scopes\EmpresaWithGlobalScope;

class Unidad extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unidades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre',
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaWithGlobalScope);
      static::addGlobalScope('globalFirst', function (Builder $builder) {
        $builder->orderBy('empresa_id', 'asc');
      });
    }

    /**
     * Incluir solo los registros globales (Que no pertenecen a una Empresa).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGlobal($query)
    {
      return $query->whereNull('empresa_id');
    }

    /**
     * Obtener la Unidad que este establecidad como Predeterminada
     *
     * @return  \App\Unidad|null
     */
    public static function predeterminada()
    {
      return self::withoutGlobalScopes()->where('status', true)->first();
    }

    /**
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener los Inventarios V2
     */
    public function inventariosV2()
    {
      return $this->hasMany('App\InventarioV2');
    }

    /**
     * Evaluar si la Unidad es Global
     *
     * @return  bool
     */
    public function isGlobal()
    {
      return is_null($this->empresa_id);
    }

    /**
     * Evaluar si la Unidad no es Global
     *
     * @return  bool
     */
    public function isNotGlobal()
    {
      return !$this->isGlobal();
    }

    /**
     * Evaluar si la Unidad es Predeterminada
     *
     * @return  bool|string
     */
    public function isPredeterminada($asBool = true)
    {
      $label = $this->status ? '<span class="label label-primary">Sí</span>' : '<span class="label label-default">No</span>';
      return $asBool ? $this->status : $label;
    }
}
