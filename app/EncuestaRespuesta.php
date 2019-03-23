<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaRespuesta extends Model
{
    protected $table = 'encuestas_respuestas';

    protected $fillable = [
      'encuesta_id',
      'pregunta_id',
      'opcion_id',
    ];

    public function usuario()
    {
      return $this->belongsTo('App\User', 'user_id');
    }

    public function pregunta()
    {
      return $this->belongsTo('App\EncuestaPregunta', 'pregunta_id');
    }

    public function opcion()
    {
      return $this->belongsTo('App\PreguntaOpcion', 'opcion_id');
    }
}
