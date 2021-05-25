<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Inventario extends model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'contrato_id',
      'tipo',
      'otro',
      'nombre',
      'valor',
      'fecha',
      'cantidad',
      'low_stock',
      'observacion',
      'descripcion',
      'calibracion',
      'certificado',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'calibracion' => 'boolean',
      'certificado' => 'boolean',
    ];

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'contrato.nombre' => 'Contrato',
      'low_stock' => 'Stock bajo',
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
     * Establecer la fecha del Inventario.
     *
     * @param  string  $value
     * @return void
     */
    public function setFechaAttribute($value)
    {
      $this->attributes['fecha'] = date('Y-m-d',strtotime($value));
    }

    /**
     * Obtener la fecha del Inventario.
     *
     * @param  string  $value
     * @return datetime
     */
    public function getFechaAttribute($value)
    {
      return date('d-m-Y', strtotime($value));
    }

    /**
     * Obtener la url de descarga del adjunto
     * 
     * @return string
     */
    public function getDownloadAttribute()
    {
      return route('admin.inventario.download', ['inventario' => $this->id]);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener las Entregas de Invetario
     */
    public function entregas()
    {
      return $this->hasMany('App\InventarioEntrega');
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
     *
     * @return string
     */
    public function tipo()
    {
      switch ($this->tipo) {
        case 1:
          $tipo = 'Insumo';
          break;
        case 2:
          $tipo = 'EPP';
          break;
        case 4:
          $tipo = 'Equipo';
          break;
        case 5:
          $tipo = 'Maquinaria';
          break;
        case 6:
          $tipo = 'Herramienta';
          break;
        case 3:
          $tipo = $this->otro ?? 'Otro';
          break;
        default:
          $tipo = 'Otro';
          break;
      }

      return $tipo;
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
    public function lowStock()
    {
      return number_format($this->low_stock, 0, ',', '.');
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
     * Obtener el directorio donde se guarda el adjunto del Inventario
     *
     * @return string
     */
    public function directory()
    {
      return 'Empresa' . Auth::user()->empresa_id . '/Inventarios/' . $this->id;
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function calibracion()
    {
      return $this->formatBoolean($this->calibracion);
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function certificado()
    {
      return $this->formatBoolean($this->certificado);
    }

    /**
     * Obtener el atributo formateado
     *
     * @param  bool  $bool 
     * @return string
     */
    protected function formatBoolean($bool)
    {
      return $bool ? '<span class="label label-primary">Sí</span>' : '<span class="label label-default">No</span>';
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
        'contrato_id'
      ])
      ->logAditionalAttributes([
        'contrato.nombre'
      ]);
    }
}
