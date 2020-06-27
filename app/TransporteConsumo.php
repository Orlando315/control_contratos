<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransporteConsumo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transportes_consumos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'tipo',
      'fecha',
      'cantidad',
      'valor',
      'chofer',
      'observacion'
    ];

    /**
     * Establecer la fecha del Consumo.
     *
     * @param  string  $value
     * @return void
     */
    public function setFechaAttribute($value)
    {
      $this->attributes['fecha'] = date('Y-m-d', strtotime($value));
    }

    /**
     * Obtener el Transporte al que pertenece
     */
    public function transporte()
    {
      return $this->belongsTo('App\Transporte');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener los Adjuntos
     */
    public function adjuntos()
    {
      return $this->hasMany('App\ConsumoAdjunto', 'consumo_id');
    }

    /**
     * Obtener el atributo formateado
     */
    public function tipo()
    {
      switch ($this->tipo) {
        case 1:
          return 'Mantenimiento';
          break;
        case 2:
          return 'Combustible';
          break;
        case 3:
          return 'Peaje';
          break;
        case 4:
          return 'Gastos varios';
          break;
        default:
          return 'Error';
          break;
      }
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function fecha()
    {
      return date('d-m-Y', strtotime($this->fecha));
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function cantidad()
    {
      return number_format($this->cantidad, 2, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function valor()
    {
      return number_format($this->valor, 2, ',', '.');
    }
}
