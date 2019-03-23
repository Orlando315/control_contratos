<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaPregunta extends Model
{
    protected $table = 'encuestas_preguntas';

    public function usuario()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    public function opciones()
    {
      return $this->hasMany('App\PreguntaOpcion', 'pregunta_id');
    }

    public function respuestas()
    {
      return $this->hasMany('App\EncuestaRespuesta', 'pregunta_id');
    }

    public function storeOpciones($requestOpciones)
    {
      $opciones = [];

      foreach (array_filter($requestOpciones) as $opcion){
        $opciones[] = [
          'opcion' => $opcion
        ];
      }

      $this->opciones()->createMany($opciones);
    }
}
