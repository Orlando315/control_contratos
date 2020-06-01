<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Facades\Auth;

class Documento extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documentos';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [
      'carpeta_id',
      'nombre',
      'path',
      'mime',
      'vencimiento'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Scope a query to only include active coupons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMain($query)
    {
      return $query->whereNull('carpeta_id');
    }

    /**
     * Obtener el Contrato al que pertenece
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener el Empleado al que pertenece
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Obtener el thumb html del Documento, con sus links
     */
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

    /**
     * Obtener el link de descarga del Documento
     */
    public function getDownloadLink()
    {
      return route('documentos.download', ['id' => $this->id]);
    }

    /**
     * Obtener el link para editar el Documento
     */
    public function getEditLink()
    {
      return route('documentos.edit', ['id' => $this->id]);
    }

    /**
     * Obtener el icono que se usara en el thumb, segun el tipo de mime del Documento
     *
     * @return string
     */
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

    /**
     * Establecer la fecha de vencimiento en el formato requerido
     * 
     * @param  string  $value
     * @return void
     */
    public function setVencimientoAttribute($value)
    {
      $this->attributes['vencimiento'] = $value ? date('Y-m-d', strtotime($value)) : null;
    }

    /**
     * Obtener la fecha de vencimiento
     * 
     * @return string
     */
    public function getVencimientoAttribute($date)
    {
      return $date ? date('d-m-Y', strtotime($date)) : null;
    }

    /**
     * Obtener los Documentos que estan por vencer
     *
     * @param  string  $model
     */
    protected static function porVencer($model = 'contrato')
    {
      $dias =  Auth::user()->empresa->configuracion->dias_vencimiento;
      $today = date('Y-m-d H:i:s');
      $less30Days = date('Y-m-d H:i:s', strtotime("{$today} +{$dias} days"));

      return self::whereNotNull('vencimiento')->whereNotNull($model.'_id')->whereBetween('vencimiento', [$today, $less30Days])->get();
    }

    /**
     * Obtener los Documentos de los Contratos que estan por vencer
     */
    public static function deContratosPorVencer()
    {
      return self::porVencer();
    }

    /**
     * Obtener los Documentos de los Empleados que estan por vencer
     */
    public static function deEmpleadosPorVencer()
    {
      return self::porVencer('empleado');
    }
}
