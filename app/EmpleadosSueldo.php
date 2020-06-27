<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class EmpleadosSueldo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empleados_sueldos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'created_at',
      'mes_pago',
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
     * Obtener la Empresa
     */
    public function empresas()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener al Empleado
     */
    public function empleado()
    {
      return $this->belongsTo('App\Empleado');
    }

    /**
     * Obtener el Contrato
     */
    public function contrato()
    {
      return $this->belongsTo('App\Contrato');
    }

    /**
     * Obtener el mes pagado junto con el año
     *
     * @return string
     */
    public function mesPagado()
    {
      setlocale(LC_ALL, 'esp');
      return ucfirst($this->mes_pago->formatLocalized('%B - %Y'));
    }

    /**
     * Obtener el nombre del Empleado
     *
     * @return string
     */
    public function nombreEmpleado()
    {
      return $this->empleado->usuario->nombres . ' ' . $this->empleado->usuario->apellidos;
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function alcanceLiquido()
    {
      return number_format($this->alcance_liquido, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function anticipo()
    {
      return number_format($this->anticipo, 0, ',', '.');
    }
    
    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function bonoReemplazo()
    {
      return number_format($this->bono_reemplazo, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function sueldoLiquido()
    {
      return number_format($this->sueldo_liquido, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function adjunto()
    {
      $link = route('sueldos.download', ['id' => $this->id]);
      return $this->adjunto ? '<a href="' . $link . '">Descargar</a>' : 'N/A';
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function recibido()
    {
      return $this->recibido == 1 ? '<span class="label label-primary">Recibido</span>' : '<span class="label label-default">Pendiente</span>';
    }
}
