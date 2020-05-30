<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Str;

class PlantillaVariable extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plantillas_variables';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'tipo', 'variable'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      static::saving(function ($variable) {
        $variable->setVariableName();
      });

      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener las variables como array para los tokens del editor de texto (CKEditor)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToFormEditor()
    {
      return self::select('nombre', 'variable')
                  ->get()
                  ->map(function ($variable) {
                    return [$variable->nombre, $variable->withoutTokens()];
                  })->toArray();
    }

    /**
     * Obtener la variable sin los tokens al inicio y final
     *
     * @return string
     */
    public function withoutTokens()
    {
      return substr($this->variable, 2, -2);
    }

    /**
     * Obtener el atributo formateado
     *
     * @param string
     */
    public function tipo()
    {
      switch ($this->tipo) {
        case 'rut':
          $tipo = 'RUT';
          break;
        case 'date':
          $tipo = 'Fecha';
          break;
        case 'tel':
          $tipo = 'Télefono';
          break;
        case 'number':
          $tipo = 'Numeros';
          break;
        case 'firma':
          $tipo = 'Firma';
          break;
        case 'text':
          $tipo = 'Texto';
        default:
          $tipo = 'Texto';
          break;
      }

      return $tipo;
    }

    /**
     * Establecer el key de la variable basado en el nombre
     */
    public function setVariableName()
    {
      $variable = Str::slug($this->nombre, '_');
      $count = 0;
      $id = $this->id ?? false;

      while ($variableExist = self::where('variable', '{{'.($count < 1 ? $variable : $variable.'_'.$count).'}}')
                                  ->when($id, function($query, $id){
                                    return $query->where('id', '!=', $id);
                                  })
                                  ->exists()){
        $count++;
      }

      $this->variable = '{{'.($count < 1 ? $variable : $variable.'_'.$count).'}}';
    }
}
