<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Auth;

class Documento extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documentos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'carpeta_id',
      'nombre',
      'path',
      'mime',
      'vencimiento',
      'created_at',
      'updated_at',
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
     * Establecer la fecha de vencimiento en el formato requerido
     * 
     * @param  string  $value
     * @return void
     */
    public function setVencimientoAttribute($value)
    {
      $this->attributes['vencimiento'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    /**
     * Obtener la fecha de vencimiento
     *
     * @param  string  $value
     * @return string
     */
    public function getVencimientoAttribute($value)
    {
      return $value ? date('d-m-Y', strtotime($value)) : null;
    }

    /**
     * Obtener la url de retorno dependiendo del modelo al que pertenezca
     *
     * @return string
     */
    public function getBackUrlAttribute()
    {
      if($this->carpeta_id){
        $route = 'carpeta.show';
        $id = $this->carpeta_id;
      }else{
        if($this->documentable_type == 'App\Contrato'){
          $route = 'contratos.show';
        }

        if($this->documentable_type == 'App\Empleado'){
          $route = 'empleados.show';
        }

        if($this->documentable_type == 'App\TransporteConsumo'){
          $route = 'consumos.show';
        }

        $id = $this->documentable_id;
      }

      return route($route, ['id' => $id]);
    }

    /**
     * Scope a query to only include active coupons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
      return $query->whereNull('carpeta_id');
    }

    /**
     * Get all of the owning documentable models.
     */
    public function documentable()
    {
      return $this->morphTo();
    }

    /**
     * Obtener los Documentos que estan por vencer
     *
     * @param  string  $model
     */
    protected static function porVencer($model = 'App\Contrato')
    {
      $dias =  Auth::user()->empresa->configuracion->dias_vencimiento;
      $today = date('Y-m-d H:i:s');
      $less30Days = date('Y-m-d H:i:s', strtotime("{$today} +{$dias} days"));

      return self::whereNotNull('vencimiento')->where('documentable_type', $model)->whereBetween('vencimiento', [$today, $less30Days])->get();
    }

    /**
     * Obtener el icono que se usara en el thumb, segun el tipo de mime del Documento
     *
     * @return string
     */
    public function getIconByMime()
    {
      switch ($this->mime) {
        case 'image/jpeg':
        case 'image/png':
          $icon = 'fa-picture-o';
          break;

        case 'application/pdf':
          $icon = 'fa-file-pdf-o';
          break;

        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
          $icon = 'fa-file-word-o';
          break;

        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
          $icon = 'fa-file-excel-o';
          break;

        case 'text/plain':
          $icon = 'fa-file-text';
          break;
        
        default:
          $icon = 'fa-file';
          break;
      }

      return $icon;
    }

    /**
     * Obtener los Documentos de los Contratos que estan por vencer
     */
    public static function deContratosPorVencer()
    {
      return self::porVencer();
    }

    /**
     * Obtener los Documentos de los Empleados que estan por vencer
     */
    public static function deEmpleadosPorVencer()
    {
      return self::porVencer('App\Empleado');
    }
}
