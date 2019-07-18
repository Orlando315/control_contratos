<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Documento extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $fillable = [
    'nombre',
    'path',
    'mime',
    'vencimiento'
  ];

  public function generateThumb()
  {
    $icon     = $this->getIconByMime();
    $download = $this->getDownloadLink();
    $edit     = $this->getEditLink();

    $vencimiento = $this->vencimiento ? '<b>Vencimiento:</b> ' . $this->vencimiento : '';

    return "<div class='info-box'>
              <span class='info-box-icon bg-red'><i class='fa {$icon}'></i></span>
              <div class='info-box-content'>
                <span class='info-box-text'>{$this->nombre}</span>
                <div class='btn-group'>
                  <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                    <i class='fa fa-cog'></i> <span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu dropdown-menu-right'>
                    <li>
                      <a title='Descargar documento' href='{$download}'>
                        <i class='fa fa-download' aria-hidden='true'></i> Descargar
                      </a>
                    </li>
                    <li>
                      <a title='Editar documento' href='{$edit}'>
                        <i class='fa fa-pencil' aria-hidden='true'></i> Editar
                      </a>
                    </li>
                    <li>
                      <a type='button' title='Eliminar archivo' data-file='{$this->id}' class='btn-delete-file' data-toggle='modal' data-target='#delFileModal'>
                        <i class='fa fa-times' aria-hidden='true'></i> Eliminar
                      </a>
                    </li>
                  </ul>
                </div>
                <p>{$vencimiento}</p>
              </div>
              <!-- /.info-box-content -->
            </div>";
  }

  public function getDownloadLink()
  {
    return route('documentos.download', ['id' => $this->id]);
  }

  public function getEditLink()
  {
    return route('documentos.edit', ['id' => $this->id]);
  }

  protected function getIconByMime()
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

  public function setVencimientoAttribute($date)
  {
    $this->attributes['vencimiento'] = $date ? date('Y-m-d', strtotime($date)) : null;
  }

  public function getVencimientoAttribute($date)
  {
    return $date ? date('d-m-Y', strtotime($date)) : null;
  }
}
