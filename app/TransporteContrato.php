<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class TransporteContrato extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transportes_contratos';
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'transporte_id',
      'contrato_id',
    ];

    /**
     * Eventos que se guardaran en Logs
     * 
     * @var array
     */
    public static $recordEvents = [
      'deleted',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Contrato de Transporte';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'transporte.patente' => 'Transporte',
      'contrato.nombre' => 'Contrato',
    ];

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
     * Opciones para personalizar los Log 
     * 
     * @return \App\Integrations\Logger\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
      return LogOptions::defaults()
      ->logExcept([
        'transporte_id',
        'contrato_id',
      ])
      ->logAditionalAttributes([
        'transporte.patente',
        'contrato.nombre',
      ]);
    }
}
