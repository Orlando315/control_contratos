<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class InventarioV2Ingreso extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventarios_ingresos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'inventario_id',
      'proveedor_id',
      'cantidad',
      'costo',
      'descripcion',
      'foto',
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
     * Obtener el Directorio
     *
     * @param  string  $value
     * @return string
     */
    public function getDirectoryAttribute($value)
    {
      return $this->inventario->directory.'/Ingresos';
    }

    /**
     * Obtener la url de la foto
     *
     * @param  string  $value
     * @return string
     */
    public function getFotoUrlAttribute($value)
    {
      return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Inventario
     */
    public function inventario()
    {
      return $this->belongsTo('App\InventarioV2');
    }

    /**
     * Obtener el Proveedor
     */
    public function proveedor()
    {
      return $this->belongsTo('App\Proveedor');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function cantidad()
    {
      return number_format($this->cantidad, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function costo()
    {
      return number_format($this->costo, 2, ',', '.');
    }
}
