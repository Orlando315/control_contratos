<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'carpeta_id',
      'nombre',
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
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Get all of the owning commentable models.
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
     * Obtener la Empresa a la que pertenece
     */
    public function documentos()
    {
      return $this->hasMany('App\Documento');
    }

    /**
     * Obtener la url de retorno segun el modelo al que pertenece 
     */
    public function getBackUrlAttribute()
    {
      $backModel = $this->isContrato() ? route('contratos.show', ['contrato' => $this->carpetable_id]) : route('empleados.show', ['empleado' => $this->carpetable_id]);
      return $this->carpeta_id ? route('carpeta.show', ['carpeta' => $this->carpeta_id]) : $backModel;
    }

    /**
     * Evaluar si la Carpeta es de un Contrato
     */
    public function isContrato()
    {
      return $this->carpetable_type == 'App\Contrato';
    }

    /**
     * Obtener el tipo de modelo al que pertenece la Carpeta
     */
    public function type()
    {
      return $this->carpetable_type == 'App\Contrato' ? 'contrato' : 'empleado';
    }

    /**
     * Obtener el thumb html de la carpeta
     *
     * @return string
     */
    public function template()
    {
      $show = route('carpeta.show', ['carpeta' => $this->id]);
      $edit = route('carpeta.edit', ['carpeta' => $this->id]);

      return "<a href=\"{$show}\">
                <div class=\"info-box\">
                  <span class=\"info-box-icon bg-yellow\"><i class=\"fa fa-folder\"></i></span>
                  <div class=\"info-box-content\">
                    <span class=\"info-box-text\" style=\"color: #333\">{$this->nombre}</span>
                  </div>
                </div>
              </a>";
    }
}
