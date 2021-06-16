<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Scopes\EmpresaScope;
use App\Traits\LogEvents;
use App\Integrations\Logger\LogOptions;

class Carpeta extends Model
{
    use LogEvents;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carpetas';

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
      'visibilidad',
      'public',
      'location',
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
     * Titulos de los atributos al mostrar el Log
     * 
     * @var array
     */
    public static $attributesTitle = [
      'main.nombre' => 'Carpeta padre',
      'requisito.nombre' => 'Requisito',
      'visibilidad' => '¿Es visible para el Empleado?',
      'public' => '¿Es pública?',
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

      static::deleting(function ($carpeta) {
        $carpeta->load([
          'subcarpetas',
          'documentos',
        ]);

        // Si la carpeta tiene su location, al eliminar fisicamente la carpeta se
        // eliminan tambien los documentos en ella y la bd se encarga de eliminar los
        // registros de esos documentos que tengan el id de la carpeta
        if($carpeta->location && Storage::exists($carpeta->location)){
          Storage::deleteDirectory($carpeta->location);
        }
        // Si no tiene su location para poder eliminarla, se debe eliminar cada documento individualmente
        // para que sean borrados fisicamente tambien
        else{
          $carpeta->documentos->each(function ($documento) {
            $documento->delete();
          });
        }

        // Se eliminan las subcarpetas para que cada una realice el mismo proceso de borrado
        $carpeta->subcarpetas->each(function ($subcarpeta) {
          $subcarpeta->delete();
        });

      });
    }

    /**
     * Scope a query to only include active coupons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
      return $query->whereNull('carpetas.carpeta_id');
    }

    /**
     * Incluir solo las Carpetas que son Requisitos.
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
     * Incluir solo las Carpetas que son visibles para el Empleado.
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
     * Incluir solo las Carpetas del tipo especificado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
      return $query->where('carpetable_type', $type);
    }

    /**
     * Incluir solo las Carpetas de la seccion Archivos
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArchivo($query)
    {
      return $query->whereNull('carpetable_type')->whereNull('carpetable_id');
    }

    /**
     * Incluir solo las Carpetas que son publicas
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $public
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query, $public = true)
    {
      return $query->where('public', $public);
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Get all of the owning carpetable models.
     */
    public function carpetable()
    {
      return $this->morphTo();
    }

    /**
     * Obtener la Carpeta a la que pertenece
     */
    public function main()
    {
      return $this->belongsTo('App\Carpeta', 'carpeta_id');
    }

    /**
     * Obtener las Carpetas
     */
    public function subcarpetas()
    {
      return $this->hasMany('App\Carpeta');
    }

    /**
     * Obtener los Documentos (Contrato/Empleado)
     */
    public function documentos()
    {
      return $this->hasMany('App\Documento');
    }

    /**
     * Obtener el Requisito
     */
    public function requisito()
    {
      return $this->belongsTo('App\Requisito');
    }

    /**
     * Obtener los User que tienen permisos de acceder a la Carpeta (De la seccion de Archivos)
     */
    public function archivoUsers()
    {
      return $this->belongsToMany('App\User', 'archivos_users', 'carpeta_id', 'user_id');
    }

    /**
     * Obtener la url de retorno segun el modelo al que pertenece 
     */
    public function getBackUrlAttribute()
    {
      $varName = self::getRouteVarNameByType($this->type());
      $backModel = Auth::user()->hasRole('empleado') ? route('perfil') : route('admin.'.$varName.'.show', [$varName => $this->carpetable_id]);
      return $this->carpeta_id ? route((Auth::user()->hasRole('empleado') ? 'carpeta.show' : 'admin.carpeta.show'), ['carpeta' => $this->carpeta_id]) : $backModel;
    }

    /**
     * Obtener la url de retorno de la Carpeta (De la seccion Archivos)
     */
    public function getBackArchivoUrlAttribute()
    {
      return $this->carpeta_id ? route('archivo.carpeta.show', ['carpeta' => $this->carpeta_id]) : route('archivo.index');
    }

    /**
     * Evaluar si la Carpeta pertenece a la clase especificada
     *
     * @param  string  $type
     * @return bool
     */
    public function isType($type)
    {
      return $this->carpetable_type == $type;
    }

    /**
     * Evaluar si la carpeta es un requisito
     *
     * @param  bool  $asTag
     * @return mixed
     */
    public function isRequisito($asTag = false)
    {
      if(!$asTag){
        return !is_null($this->requisito); 
      }

      return $this->isRequisito() ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Evaluar si la carpeta es visible para el Empleado al que pertenece
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
     * Evaluar si la carpeta (De la seccion Archivos) es publica
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
     * Evaluar si la carpeta (De la seccion Archivos) NO es publica
     *
     * @param  bool  $asTag
     * @return mixed
     */
    public function isPrivate($asTag = false)
    {
      return !$this->isPublic($asTag);
    }

    /**
     * Evaluar si la carpeta es una carpeta principal (no tiene subcarptas)
     * 
     * @return bool
     */
    public function isMain()
    {
      return is_null($this->carpeta_id);
    }

    /**
     * Evaluar si la carpeta es una subcarpeta
     * 
     * @return bool
     */
    public function isSubfolder()
    {
      return !$this->isMain();
    }

    /**
     * Evaluar si la carpeta es de la seccion Archivos
     * 
     * @return bool
     */
    public function isArchivo()
    {
      return is_null($this->carpetable_type) && is_null($this->carpetable_id);
    }

    /**
     * Obtener el modelo al que pertenece la Carpeta
     *
     * @return string
     */
    public function type()
    {
      return self::getTypeFromClass($this->carpetable_type);
    }

    /**
     * Obtener la clase segun el tipo especificado
     * 
     * @param  string  $type
     * @return string
     */
    public static function getModelClass($type)
    {
      switch ($type){
        case 'contratos':
          return 'App\Contrato';
          break;
        case 'empleados':
          return 'App\Empleado';
          break;
        case 'consumos':
          return 'App\TransporteConsumo';
          break;
        case 'transportes':
          return 'App\Transporte';
          break;
        default:
          abort(404);
        break;
      } 
    }

    /**
     * Obtener el type segun la clase especificada
     * 
     * @param  string  $class
     * @return string
     */
    public static function getTypeFromClass($class)
    {
      switch ($class) {
        case 'App\Contrato':
          return 'contratos';
          break;
        case 'App\Empleado':
          return 'empleados';
          break;
        case 'App\TransporteConsumo':
          return 'consumos';
          break;
        case 'App\Transporte':
          return 'transportes';
          break;
      }
    }

    /**
     * Obtener el type segun la clase especificada
     * 
     * @param  string  $class
     * @return string
     */
    public static function getRouteVarNameByType($type)
    {
      return substr($type, 0, -1);
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
        'location',
      ])
      ->logAditionalAttributes([
        'main.nombre',
      ]);
    }
}
