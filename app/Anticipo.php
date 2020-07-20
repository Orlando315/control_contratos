<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\{EmpresaScope, LatestScope};

class Anticipo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anticipos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'empleado_id',
      'fecha',
      'anticipo',
      'bono',
      'descripcion',
      'adjunto',
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
      static::addGlobalScope(new LatestScope);
    }

    /**
     * Establecer el atributo formateado
     * 
     * @param  string  $value
     * @return void
     */
    public function setFechaAttribute($value)
    {
      $this->attributes['fecha'] = date('Y-m-d', strtotime($value));
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
     * Obtener directorio para los adjuntos
     * 
     * @param  string  $value
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->empresa_id.'/Empleado'.$this->empleado_id.'/Anticipos';
    }

    /**
     * Obtener el enlace de descarga del adjunto
     * 
     * @return string
     */
    public function getAdjuntoDownloadAttribute()
    {
      return $this->adjunto ? route('admin.anticipos.download', ['anticipo' => $this->id]) : null;
    }

    /**
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener el Empleado
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Obtener el atributo formateado
     */
    public function anticipo()
    {
      return number_format($this->anticipo, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     */
    public function bono()
    {
      return number_format($this->bono, 0, ',', '.');
    }
}
