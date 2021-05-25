<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class TransporteConsumo extends Model
{
    use LogEvents;

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
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Consumo de Transporte';

    /**
     * Nombre base de las rutas
     * 
     * @var string
     */
    public static $baseRouteName = 'consumo';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'transporte_id' => 'Transporte',
      'contrato.nombre' => 'Contrato',
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
     * Obtener las Carpetas
     */
    public function carpetas()
    {
      return $this->morphMany('App\Carpeta', 'carpetable');
    }

    /**
     * Obtener los Documentos
     */
    public function documentos()
    {
      return $this->morphMany('App\Documento', 'documentable');
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
      ])
      ->logAditionalAttributes([
        'transporte_id',
        'contrato.nombre',
      ]);
    }
}
