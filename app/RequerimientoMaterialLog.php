<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\LatestScope;

class RequerimientoMaterialLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requerimientos_materiales_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'type',
      'message',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new LatestScope);
    }

    /**
     * Filtrar por el tipo de log proporcionado
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type = 'firmante')
    {
      return $query->where('type', $type);
    }

    /**
     * Obtener RequerimientoMateria al que pertenece
     */
    public function requerimiento()
    {
      return $this->belongsTo('App\RequerimientoMaterial', 'requerimiento_id');
    }

    /**
     * Obtener RequerimientoMateria al que pertenece
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function type()
    {
      return '<span class="label label-primary">'.(ucfirst($this->type)).'</span>';
    }
}
