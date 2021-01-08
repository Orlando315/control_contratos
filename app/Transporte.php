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
     * Obtener el Usuario
     */
    public function usuario()
    {
      return $this->belongsTo('App\User', 'user_id');
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
      return $this->belongsToMany('App\Contrato', 'transportes_contratos', 'transporte_id', 'contrato_id');
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
     * Obtener la Faena
     */
    public function faena()
    {
      return $this->belongsTo('App\Faena');
    }

    /**
     * Obtener los Requisitos (Documetos) del Contrato especificado
     *
     * @param  \App\Contrato  $contrato
     */
    public function requisitosWithDocumentos($contrato)
    {
      $documentosRequisitos = $this->documentos()->requisito()->distinct('requisito_id')->get();

      return $contrato->requisitos()
                  ->ofType('transportes')
                  ->get()
                  ->map(function ($requisito) use ($documentosRequisitos) {
                    $requisito->documento = $documentosRequisitos->firstWhere('requisito_id', $requisito->id);
                    return $requisito;
                  });
    }

    /**
     * Obtener los Requisitos que aun no tienen un Documento agregado
     */
    public function requisitosFaltantes()
    {
      $ids = $this->documentos()->requisito()->distinct('requisito_id')->pluck('requisito_id');      
      $requisitos =  $this->parentContratos->flatMap(function ($contrato) use ($ids){
        return $contrato->requisitos()->ofType('transportes')->whereNotIn('id', $ids)->get();
      });

      return $requisitos;
    }
}
