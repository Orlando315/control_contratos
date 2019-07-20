<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class EmpleadosSueldo extends Model
{

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope(new EmpresaScope);
  }

  protected $fillable = [
    'contrato_id',
    'empleado_id',
    'alcance_liquido',
    'asistencias',
    'anticipo',
    'bono_reemplazo',
    'sueldo_liquido',
    'adjunto',
    'mes_pago',
  ];

  protected $dates = [
    'created_at',
    'mes_pago',
  ];

  public function empresas()
  {
    return $this->belongsTo('App\Empresa');
  }

  public function empleado()
  {
    return $this->belongsTo('App\Empleado');
  }

  public function contrato()
  {
    return $this->belongsTo('App\Contrato');
  }

  public function mesPagado()
  {
    setlocale(LC_ALL, 'esp');
    return ucfirst($this->mes_pago->formatLocalized('%B - %Y'));
  }

  public function nombreEmpleado()
  {
    return $this->empleado->usuario->nombres . ' ' . $this->empleado->usuario->apellidos;
  }

  public function alcanceLiquido()
  {
    return number_format($this->alcance_liquido, 0, ',', '.');
  }

  public function anticipo()
  {
    return number_format($this->anticipo, 0, ',', '.');
  }
  
  public function bonoReemplazo()
  {
    return number_format($this->bono_reemplazo, 0, ',', '.');
  }

  public function sueldoLiquido()
  {
    return number_format($this->sueldo_liquido, 0, ',', '.');
  }

  public function adjunto()
  {
    return $this->adjunto ? '<a href="' . $this->getDownloadLink() . '">Descargar</a>' : 'N/A';
  }

  protected function getDownloadLink()
  {
    return route('sueldos.download', ['id' => $this->id]);
  }

  public function recibido()
  {
    return $this->recibido == 1 ? '<span class="label label-primary">Recibido</span>' : '<span class="label label-default">Pendiente</span>';
  }
}
