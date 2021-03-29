<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class InventarioV2Egreso extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventarios_egresos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'inventario_id',
      'user_id',
      'cliente_id',
      'contrato_id',
      'faena_id',
      'centro_costo_id',
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
      return $this->inventario->directory.'/Egresos';
    }

    /**
     * Obtener el Directorio
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
     * Obtener el User
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * Obtener el Cliente
     */
    public function cliente()
    {
      return $this->belongsTo('App\Cliente');
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener la Faena
     */
    public function faena()
    {
      return $this->belongsTo('App\Faena');
    }

    /**
     * Obtener el CentroCosto
     */
    public function centroCosto()
    {
      return $this->belongsTo('App\CentroCosto');
    }

    /**
     * Evaluar si el Egreso no es para un User ni un Cliente
     * 
     * @return bool
     */
    public function isEmpty()
    {
      return !$this->isUser() && !$this->isCliente();
    }

    /**
     * Evaluar si el Egreso es para un User
     * 
     * @return bool
     */
    public function isUser()
    {
      return !is_null($this->user);
    }

    /**
     * Evaluar si el Egreso es para un Cliente
     * 
     * @return bool
     */
    public function isCliente()
    {
      return !is_null($this->cliente);
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

    /**
     * Obtener a que tipo esta dirigido el Egreso
     * 
     * @return string
     */
    public function tipo()
    {
      if($this->isEmpty()){
        return '';
      }

      return $this->isCliente() ? 'cliente' : 'usuario';
    }
}
