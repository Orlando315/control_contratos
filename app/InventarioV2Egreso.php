<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class InventarioV2Egreso extends Model
{
    use LogEvents;

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
      'recibido',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'recibido',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Egreso de Inventario V2';

    /**
     * Nombre base de las rutas
     * 
     * @var string
     */
    public static $baseRouteName = 'inventario.egreso';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'inventario.nombre' => 'Inventario',
      'user.nombreCompleto' => 'Usuario',
      'cliente.nombre' => 'Cliente',
      'contrato.nombre' => 'Contrato',
      'faena.nombre' => 'Faena',
      'centroCosto.nombre' => 'Centro de Costo',
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
     * Incluir solo los registros pendintes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendiente($query)
    {
      return $query->whereNull('recibido');
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
     * Evaluar si el Egreso fue marcado como recibido
     * 
     * @return bool
     */
    public function isRecibido()
    {
      return !$this->isPending();
    }

    /**
     * Evaluar si el Egreso no ha sido marcado como recibido
     * 
     * @return bool
     */
    public function isPending()
    {
      return is_null($this->recibido);
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
        return null;
      }

      return $this->isCliente() ? 'Cliente' : 'Usuario';
    }

    /**
     * Obtener el atributo formateado
     *
     * @param  bool  $asText
     */
    public function recibido($asText = false)
    {
      $recibido = $this->isPending() ? '<span class="label label-default">Pendiente</span>' : '<span class="label label-primary">Recibido</span>';

      return $asText ? strip_tags($recibido) : $recibido;
    }

    /**
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults()
      ->logExcept([
        'empresa_id',
        'inventario_id',
        'user_id',
        'cliente_id',
        'contrato_id',
        'faena_id',
        'centro_costo_id',
      ])
      ->logAditionalAttributes([
        'inventario.nombre',
        'user.nombreCompleto',
        'cliente.nombre',
        'contrato.nombre',
        'faena.nombre',
        'centroCosto.nombre',
      ]);
    }
}
