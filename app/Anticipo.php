<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
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
     * Filtro para obtener solo los Anticipos aprobados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAprobados($query)
    {
      return $query->where('status', true);
    }

    /**
     * Filtro para obtener solo los Anticipos Pendientes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendientes($query)
    {
      return $query->whereNull('status');
    }

    /**
     * Filtro para obtener solo los Anticipos Rechazados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRechazados($query)
    {
      return $query->where('status', false);
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
      return $this->adjunto ? route((Auth::user()->isEmpleado() ? 'anticipos.download' : 'admin.anticipos.download'), ['anticipo' => $this->id]) : null;
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

    /**
     * Evaluar si el Anticipo fue aprobado
     * 
     * @return boolean
     */
    public function isAprobado()
    {
      return $this->status === true;
    }

    /**
     * Evaluar si el Anticipo esta pendiente por aprobar / rechazar
     * 
     * @return boolean
     */
    public function isPendiente()
    {
      return is_null($this->status);
    }

    /**
     * Evaluar si el Anticipo fue rechazado
     * 
     * @return boolean
     */
    public function isRechazado()
    {
      return $this->status === false;
    }

    /**
     * Obtener el status formateado como label
     *
     * @return string
     */
    public function status()
    {
      if(is_null($this->status)){
        return '<span class="label label-default">Pendiente</span>';
      }

      return $this->status ? '<span class="label label-primary">Aprobado</span>' : '<span class="label label-danger">Rechazado</span>';
    }
}
