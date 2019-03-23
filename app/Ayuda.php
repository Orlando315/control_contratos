<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ayuda extends Model
{
    protected $table = 'ayudas';

    protected $fillable = [
      'titulo',
      'contenido',
      'video',
    ];

    public function usuario()
    {
      return $this->belongsTo('App\User', 'user_id');
    }
}
