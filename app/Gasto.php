<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Gasto extends Model
{  
    protected static function boot()
    {
      parent::boot();

      static::addGlobalScope(new EmpresaScope);
    }

    protected $fillable = [
      'contrato_id',
      'etiqueta_id',
      'nombre',
      'valor',
    ];

    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    public function etiqueta()
    {
      return $this->belongsTo('App\Etiqueta');
    }

    public function valor()
    {
      return number_format($this->valor, 0, ',', '.');
    }

}
