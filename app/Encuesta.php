<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'encuestas';

    protected $fillable = [
      'titulo'
    ];

    public function usuario()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    public function preguntas()
    {
      return $this->hasMany('App\EncuestaPregunta', 'encuesta_id');
    }

    public function respuestas()
    {
      return $this->hasMany('App\EncuestaRespuesta', 'encuesta_id');
    }

    public function respuestasGroupByUser()
    {
      return $this->respuestas()->groupBy('user_id');
    }

    public function validatePreguntas()
    {
      $fields = [];

      foreach ($this->preguntas()->get() as $pregunta) {
        $fields['pregunta.' . $pregunta->id] = 'required';
      }

      return $fields;
    }

    public function createRespuestas(array $requestPregunta){
      $respuestas = [];
      
      foreach ($this->preguntas()->get() as $pregunta){
        $respuestas[] = [
          'encuesta_id' => $this->id,
          'pregunta_id' => $pregunta->id,
          'opcion_id'   => (int)$requestPregunta[$pregunta->id],
        ];
      }

      return $respuestas;
    }
}
