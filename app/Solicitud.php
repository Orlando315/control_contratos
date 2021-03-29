<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\{EmpresaScope, LatestScope};

class Solicitud extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'solicitudes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'empleado_id',
      'tipo',
      'otro',
      'descripcion',
      'status',
      'adjunto',
      'observacion',
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
     * Filtro para obtener solo las Solicitudes aprobados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAprobados($query)
    {
      return $query->where('status', true);
    }

    /**
     * Filtro para obtener solo las Solicitudes Pendientes
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendientes($query)
    {
      return $query->whereNull('status');
    }

    /**
     * Filtro para obtener solo las Solicitudes Rechazados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRechazados($query)
    {
      return $query->where('status', false);
    }

    /**
     * Obtener directorio para los adjuntos
     * 
     * @param  string  $value
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->empresa_id.'/Empleado'.$this->empleado_id.'/Solicitudes/';
    }

    /**
     * Obtener el enlace de descarga del adjunto
     * 
     * @return string
     */
    public function getDownloadAttribute()
    {
      return $this->adjunto ? route('solicitud.download', ['solicitud' => $this->id]) : null;
    }

    /**
     * Obtener la Empresa
     */
    public function empresas()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener al Empleado
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Evaluar si la Solicitud fue aprobada
     * 
     * @return boolean
     */
    public function isAprobado()
    {
      return $this->status === true;
    }

    /**
     * Evaluar si la Solicitud esta pendiente por aprobar / rechazar
     * 
     * @return boolean
     */
    public function isPendiente()
    {
      return is_null($this->status);
    }

    /**
     * Evaluar si la Solicitud fue rechazada
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
        return '<small class="label label-default">Pendiente</small>';
      }

      return $this->status ? '<small class="label label-primary">Aprobado</small>' : '<small class="label label-danger">Rechazado</small>';
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function tipo()
    {
      switch ($this->tipo) {
        case 'certificado':
          $tipo = 'Certificado laboral';
          break;
        case 'otro':
          $tipo = $this->otro ?? 'Otro';
          break;
        default:
          $tipo = 'Otro';
          break;
      }

      return $tipo;
    }
}
