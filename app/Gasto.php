<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Gasto extends Model
{  
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gastos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'contrato_id',
      'etiqueta_id',
      'nombre',
      'valor',
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
     * Obtener el Contrato a al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener la Etiqueta a la que pertenece
     */
    public function etiqueta()
    {
      return $this->belongsTo('App\Etiqueta');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function valor()
    {
      return number_format($this->valor, 0, ',', '.');
    }
}
