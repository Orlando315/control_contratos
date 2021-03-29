<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Factura extends Model
{
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
      'etiqueta_id',
      'tipo',
      'nombre',
      'realizada_para',
      'realizada_por',
      'fecha',
      'valor',
      'pago_fecha',
      'pago_estado',
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
}
