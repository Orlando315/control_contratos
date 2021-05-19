<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Scopes\EmpresaScope;

class Carpeta extends Model
{
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
      'nombre',
      'visibilidad',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'visibilidad' => 'boolean',
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
     * Obtener la Empresa a la que pertenece
     */
    public function main()
    {
      return $this->belongsTo('App\Carpeta');
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
     * Obtener la url de retorno segun el modelo al que pertenece 
     */
    public function getBackUrlAttribute()
    {
      $varName = self::getRouteVarNameByType($this->type());
      $backModel = Auth::user()->hasRole('empleado') ? route('perfil') : route('admin.'.$varName.'.show', [$varName => $this->carpetable_id]);
      return $this->carpeta_id ? route((Auth::user()->hasRole('empleado') ? 'carpeta.show' : 'admin.carpeta.show'), ['carpeta' => $this->carpeta_id]) : $backModel;
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
        case 'inventarios':
          return 'App\Inventario';
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
        case 'App\Inventario':
          return 'inventarios';
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
}
