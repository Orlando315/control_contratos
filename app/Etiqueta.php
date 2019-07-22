<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Etiqueta extends Model
{
    protected static function boot()
    {
      parent::boot();

      static::addGlobalScope(new EmpresaScope);
    }

    protected $table = 'etiquetas';

    protected $fillable = [
      'etiqueta'
    ];

    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    public function gastos()
    {
      return $this->hasMany('App\Gasto');
    }
}
