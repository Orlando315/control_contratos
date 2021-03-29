<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantillaSeccion extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plantillas_secciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'contenido', 'variables'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      static::saving(function ($model) {
        $model->setVariablesFromContent();
      });
    }

    /**
     * Establecer las variables (PlantillaVariables) usadas em el contenido de la PlantillaSeccion
     */
    public function setVariablesFromContent()
    {
      $pattern = '/{{([a-z])+([\w]+)?}}/';
      preg_match_all($pattern, $this->contenido, $matches);

      $this->variables = PlantillaVariable::select('id','nombre', 'tipo', 'variable')->whereIn('variable', $matches[0])->get();
    }
}
