<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreguntaOpcion extends Model
{
    protected $table = 'preguntas_opciones';

    protected $fillable = [
      'opcion'
    ];

    public $timestamps = false;

    public function pregunta()
    {
      return $this->belongsTo('App\EncuestaPregunta', 'pregunta_id');
    }

    public function respuestas()
    {
      return $this->hasMany('App\EncuestaRespuesta', 'opcion_id');
    }

    public function portencaje(){
      $total = $this->pregunta->respuestas()->count();

      if($total > 0){
        $respuestas = $this->respuestas->count();
        return ($respuestas / $total) * 100;
      }

      return 0;
    }
}
