<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Transporte extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transportes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'vehiculo',
      'patente',
      'modelo',
      'marca',
      'color',
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
     * Obtener las relaciones de Contratos a los que esta asociado
     */
    public function contratos()
    {
      return $this->hasMany('App\TransporteContrato');
    }

    /**
     * Obtener los Contratos a los que esta asociado
     */
    public function parentContratos()
    {
      return $this->belongsToMany('App\Contrato', 'transportes_contratos', 'transporte_id', 'contrato_id')->withTimestamps();
    }

    /**
     * Obtener los supervisores
     */
    public function supervisores()
    {
      return $this->belongsToMany('App\User', 'transportes_supervisores', 'transporte_id', 'user_id')->withTimestamps();
    }

    /**
     * Obtener los faenas
     */
    public function faenas()
    {
      return $this->belongsToMany('App\Faena', 'transportes_faenas', 'transporte_id', 'faena_id')->withTimestamps();
    }

    /**
     * Obtener lso Consumos
     */
    public function consumos()
    {
      return $this->hasMany('App\TransporteConsumo');
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
     * Obtener los Requisitos (Documetos/Carpetas) del Contrato especificado
     *
     * @param  \App\Contrato  $contrato
     */
    public function requisitosWithDocumentos($contrato)
    {
      $documentosRequisitos = $this->documentos()->requisito()->distinct('requisito_id')->get();
      $carpetasRequisitos = $this->carpetas()->requisito()->distinct('requisito_id')->get();

      return $contrato->requisitos()
                  ->ofType('transportes')
                  ->get()
                  ->map(function ($requisito) use ($documentosRequisitos, $carpetasRequisitos) {
                    $requisitos = $requisito->isFolder() ? $carpetasRequisitos : $documentosRequisitos;
                    $requisito->documento = $requisitos->firstWhere('requisito_id', $requisito->id);
                    return $requisito;
                  });
    }

    /**
     * Obtener los Requisitos que aun no tienen un Documento/Carpeta agregado
     *
     * @param  bool  $folder
     */
    public function requisitosFaltantes($folder = false)
    {
      $ids = $this->documentos()->requisito()->distinct('requisito_id')->pluck('requisito_id');
      $requisitos =  $this->parentContratos->flatMap(function ($contrato) use ($ids, $folder){
        return $contrato->requisitos()->ofType('transportes')->where('folder', $folder)->whereNotIn('id', $ids)->get();
      });

      return $requisitos;
    }

    /**
     * Obtener las Faenas como Tags
     * 
     * @return string
     */
    public function faenasTags()
    {
      $faenas = $this->faenas
      ->transform(function ($faena){
        return $faena->asTag();
      })
      ->toArray();

      return implode(' ', $faenas);
    }
}
