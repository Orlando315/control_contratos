<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Covid19Pregunta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'covid19_preguntas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'pregunta',
      'type',
      'order',
    ];

    /**
     * Evaluar si la pregunta es de tipo bool
     *
     * @return bool
     */
    public function isBool()
    {
      return $this->type == 'bool';
    }

    /**
     * Evaluar si la pregunta es de tipo string
     *
     * @return bool
     */
    public function isText()
    {
      return $this->type == 'text';
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function type()
    {
      return $this->isBool() ? '<small class="label label-primary">Bool</small>' : '<small class="label label-success">Text</small>';
    }
}
