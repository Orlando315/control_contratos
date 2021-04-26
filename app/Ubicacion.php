<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Ubicacion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ubicaciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'bodega_id',
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
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener los Bodega
     */
    public function bodega()
    {
      return $this->belongsTo('App\Bodega');
    }

    /**
     * Obtener los Inventarios V2
     */
    public function inventariosV2()
    {
      return $this->hasMany('App\InventarioV2');
    }
}
