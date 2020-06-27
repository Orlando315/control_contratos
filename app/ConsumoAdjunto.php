<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumoAdjunto extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    public $table = 'consumos_adjuntos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre',
      'path',
      'mime',
      'vencimiento'
    ];

    /**
     * Obtener el icono según el mime.
     */
    public function getIconByMime()
    {
      switch ($this->mime) {
        case 'image/jpeg':
        case 'image/png':
          $icon = 'fa-picture-o';
          break;

        case 'application/pdf':
          $icon = 'fa-file-pdf-o';
          break;

        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
          $icon = 'fa-file-word-o';
          break;

        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
          $icon = 'fa-file-excel-o';
          break;

        case 'text/plain':
          $icon = 'fa-file-text';
          break;
        
        default:
          $icon = 'fa-file';
          break;
      }

      return $icon;
    }
}
