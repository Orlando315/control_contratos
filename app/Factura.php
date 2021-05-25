<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Factura extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facturas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'partida_id',
      'etiqueta_id',
      'tipo',
      'faena_id',
      'centro_costo_id',
      'proveedor_id',
      'nombre',
      'realizada_para',
      'realizada_por',
      'fecha',
      'valor',
      'pago_fecha',
      'pago_estado',
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'contrato.nombre' => 'Contrato',
      'partia.nombre' => 'Partida',
      'etiqueta.etiqueta' => 'Etiqueta',
      'faena.nombre' => 'Faena',
      'centroCosto.nombre' => 'Centro de costo',
      'proveedor.nombre' => 'Proveedor',
      'pago_fecha' => 'Fecha de pago',
      'pago_estado' => 'Estado del pago',
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
     * Establecer el atributo formateado
     * 
     * @param  string  $value
     * @return void
     */
    public function setFechaAttribute($value)
    {
      $this->attributes['fecha'] = date('Y-m-d',strtotime($value));
    }

    /**
     * Obtener el atributo formateado
     * 
     * @param  string  $value
     * @return string
     */
    public function getFechaAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Establecer el atributo formateado
     * 
     * @param  string  $value
     * @return void
     */
    public function setPagoFechaAttribute($value)
    {
      $this->attributes['pago_fecha'] = date('Y-m-d',strtotime($value));
    }

    /**
     * Obtener el atributo formateado
     * 
     * @param  string  $value
     * @return string
     */
    public function getPagoFechaAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Obtener el Contrato a la que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener La Etiqueta a la que pertenece
     */
    public function etiqueta()
    {
      return $this->belongsTo('App\Etiqueta');
    }

    /**
     * Obtener la Partida (De Contratos)
     */
    public function partida()
    {
      return $this->belongsTo('App\Partida');
    }

    /**
     * Obtener la Faena
     */
    public function faena()
    {
      return $this->belongsTo('App\Faena');
    }

    /**
     * Obtener la Proveedor
     */
    public function proveedor()
    {
      return $this->belongsTo('App\Proveedor');
    }

    /**
     * Obtener la CentroCosto
     */
    public function centroCosto()
    {
      return $this->belongsTo('App\CentroCosto');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function tipo()
    {
      return $this->tipo == 1 ? 'Ingreso' : 'Egreso';
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function valor()
    {
      return number_format($this->valor, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function pago()
    {
      return $this->pago_estado == 1 ? '<span class="label label-primary">Pagada</span>' : '<span class="label label-default">Pendiente</span>';
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function directory()
    {
      return 'Empresa' . $this->empresa_id . '/Facturas/' . $this->id;
    }

    /**
     * Evaluar si el adjunto proporcionado, existe
     *
     * @param  int  $adjunto
     * @return bool
     */
    public function adjuntoExist($adjunto)
    {
      return !is_null($this->{"adjunto{$adjunto}"});
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
        'contrato_id',
        'partida_id',
        'etiqueta_id',
        'faena_id',
        'centro_costo_id',
        'proveedor_id',
      ])
      ->logAditionalAttributes([
        'contrato.nombre',
        'partida.nombre',
        'etiqueta.etiqueta',
        'faena.nombre',
        'centroCosto.nombre',
        'proveedor.nombre',
      ]);
    }
}
