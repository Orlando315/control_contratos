<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Carpeta;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Documento extends Model
{
    use LogEvents;

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
      'requisito_id',
      'nombre',
      'observacion',
      'path',
      'mime',
      'vencimiento',
      'visibilidad',
      'public',
      'created_at',
      'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'visibilidad' => 'boolean',
      'public' => 'boolean',
    ];

    /**
     * Titulo del modelo en los Logs
     * 
     * @var string
     */
    public static $logEventTitle = 'Documento adjunto';

    /**
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'carpeta.nombre' => 'Carpeta padre',
      'requisito.nombre' => 'Requisito',
      'visibilidad' => '¿Es visible para el Empleado?',
      'public' => '¿Es público?',
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

      // Eliminar fisicamente el documento
      static::deleting(function ($documento) {
        if($documento->path && Storage::exists($documento->path)){
          Storage::delete($documento->path); 
        }
      });
    }

    /**
     * Filtro para obtener Documentos sin carpetas
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
      return $query->whereNull('documentos.carpeta_id');
    }

    /**
     * Incluir solo los Documentos que son Requisitos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $isRequisito
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRequisito($query, $isRequisito = true)
    {
      return $isRequisito ? $query->whereNotNull('requisito_id') : $query->whereNull('requisito_id');
    }

    /**
     * Incluir solo los Documentos del tipo especificado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
      return $query->where('documentable_type', $type);
    }

    /**
     * Filtro para obtener los registros expirados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
      $now = date('Y-m-d H:i:s');
      return $query->whereNotNull('vencimiento')->where('vencimiento', '<=', $now);
    }

    /**
     * Filtro para obtener los registos expirados por el tipo especificado
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiredByType($query, $type)
    {
      return $query->byType($type)->expired();
    }

    /**
     * Filtro para obtener los registros que estan por vencer faltando los dias especificados
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $model
     * @param  int  $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeAboutToExpireByType($query, string $model, $days)
    {
      $now = date('Y-m-d H:i:s');
      $plusDays = date('Y-m-d H:i:s', strtotime("{$now} +{$days} days"));

      return $query->byType($model)->whereNotNull('vencimiento')->whereBetween('vencimiento', [$now, $plusDays]);
    }

    /**
     * Incluir solo los Documentos que son visibles para el Empleado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $isVisible
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query, $isVisible = true)
    {
      return $query->where('visibilidad', $isVisible);
    }

    /**
     * Incluir solo los Documentos de la seccion Archivos
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchivo($query)
    {
      return $query->whereNull('documentable_type')->whereNull('documentable_id');
    }

    /**
     * Incluir solo los Documentos que son publicos.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $isPublic
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query, $isPublic = true)
    {
      return $query->where('public', $isPublic);
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
        $route = 'admin.carpeta.show';
        $id = $this->carpeta_id;
        $varName = 'carpeta';
      }else{
        if($this->documentable_type == 'App\Contrato'){
          $route = 'admin.contrato.show';
        }

        if($this->documentable_type == 'App\Empleado'){
          $route = 'admin.empleado.show';
        }

        if($this->documentable_type == 'App\TransporteConsumo'){
          $route = 'admin.consumo.show';
        }

        if($this->documentable_type == 'App\Transporte'){
          $route = 'admin.transporte.show';
        }

        $varName = Carpeta::getRouteVarNameByType($this->type());
        $id = $this->documentable_id;
      }

      return route($route, [$varName => $id]);
    }

    /**
     * Obtener la url de retorno del documento (De la seccion Archivos)
     */
    public function getBackArchivoUrlAttribute()
    {
      return $this->carpeta_id ? route('archivo.carpeta.show', ['carpeta' => $this->carpeta_id]) : route('archivo.index');
    }

    /**
     * Obtener el Link del documento
     * 
     * @return string
     */
    public function getAssetUrlAttribute()
    {
      return asset('storage/'.$this->path);
    }

    /**
     * Obtener el Link de descarga del documento
     * 
     * @return string
     */
    public function getDownloadAttribute()
    {
      return route('documento.download', ['documento' => $this->id]);
    }

    /**
     * Get all of the owning documentable models.
     */
    public function documentable()
    {
      return $this->morphTo();
    }

    /**
     * Obtener la Carpeta a la que pertenece
     */
    public function carpeta()
    {
      return $this->belongsTo('App\Carpeta');
    }

    /**
     * Obtener el Requisito al que pertenece
     */
    public function requisito()
    {
      return $this->belongsTo('App\Requisito');
    }

    /**
     * Obtener los User que tienen permisos de acceder al Documento (De la seccion de Archivos)
     */
    public function archivoUsers()
    {
      return $this->belongsToMany('App\User', 'archivos_users', 'documento_id', 'user_id');
    }

    /**
     * Evaluar si el Documento pertenece a la clase especificada
     *
     * @param  string  $type
     * @return bool
     */
    public function isType($type)
    {
      return $this->documentable_type == $type;
    }

    /**
     * Evaluar si el Documento es un Requisito
     *
     * @return bool
     */
    public function isRequisito()
    {
      return !is_null($this->requisito_id);
    }

    /**
     * Evaluar si el Documento es un PDF
     * 
     * @return boolean
     */
    public function isPdf()
    {
      return $this->mime == 'application/pdf';
    }

    /**
     * Evaluar si el Documento es visible para el Empleado al que pertenece
     *
     * @param  bool  $asTag
     * @return mixed
     */
    public function isVisible($asTag = false)
    {
      if(!$asTag){
        return $this->visibilidad;
      }
      return $this->visibilidad ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Evaluar si la Carpeta es de tipo Empleado
     * 
     * @return bool
     */
    public function isTypeEmpleado()
    {
      return $this->isType('App\Empleado');
    }

    /**
     * Evaluar si el documento (De la seccion Archivos) es publico
     *
     * @param  bool  $asTag
     * @return mixed
     */
    public function isPublic($asTag = false)
    {
      if(!$asTag){
        return $this->public;
      }

      return $this->public ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Evaluar si el documento (De la seccion Archivos) NO es publico
     *
     * @param  bool  $asTag
     * @return mixed
     */
    public function isPrivate($asTag = false)
    {
      return !$this->isPublic($asTag);
    }

    /**
     * Evaluar si el documento es de la seccion Archivos
     * 
     * @return bool
     */
    public function isArchivo()
    {
      return is_null($this->documentable_type) && is_null($this->documentable_id);
    }

    /**
     * Obtener el modelo al que pertenece la Carpeta
     *
     * @return string
     */
    public function type()
    {
      return Carpeta::getTypeFromClass($this->documentable_type);
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
     * Obtener los Documentos de que estan por vencer por el type proporcionado
     *
     * @param  string  $type
     * @return  object
     */
    public static function groupedAboutToExpireByType(string $type)
    {
      $model = Carpeta::getModelClass($type);
      $vencidos = self::expiredByType($model)->count();
      $lessThan3 = self::aboutToExpireByType($model, 3)->count();
      $lessThan7 = self::aboutToExpireByType($model, 7)->count();
      $lessThan21 = self::aboutToExpireByType($model, 21)->count();

      return (object)[
        'vencidos' => $vencidos,
        'lessThan3' => $lessThan3,
        'lessThan7' => $lessThan7,
        'lessThan21' => $lessThan21,
      ];
    }

    /**
     * Obtener la extension del archivo
     * 
     * @return string
     */
    public function getExtension()
    {
      return explode('.', $this->path)[1];
    }

    /**
     * Evaluar si el User autenticado puede descarga el Documento
     * 
     * @return bool
     */
    public function canUserDownload()
    {
      if(
        Auth::user()->hasPermission($this->getRequiredPermission()) ||
        (Auth::user()->hasRole('empleado') && $this->isTypeEmpleado() && $this->isVisible() && Auth::user()->empleado_id == $this->documentable_id)
      ){
        return true;
      }

      return false;
    }

    /**
     * Obtener el tipo de permiso necesario para acceder al Documento
     * 
     * @return string
     */
    private function getRequiredPermission()
    {
      switch ($this->documentable_type) {
        case 'App\Contrato':
          return 'contrato-view';
          break;

        case 'App\Empleado':
          return 'empleado-view';
          break;

        case 'App\TransporteConsumo':
          return 'transporte-consumo-view';
          break;

        case 'App\Transporte':
          return 'transporte-view';
          break;
      }
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
        'empresa_id',
        'carpeta_id',
        'requisito_id',
        'path',
        'mime',
        'created_at',
        'updated_at'
      ])
      ->logAditionalAttributes([
        'carpeta.nombre',
        'requisito.nombre',
      ]);
    }
}
