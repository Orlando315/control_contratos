<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;

class Pago extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'facturacion_id',
      'metodo',
      'metodo_otro',
      'monto',
      'adjunto',
      'descripcion',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
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
     * Obtener el path del directorio donde se guardaran los archivos
     * 
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return $this->cotizacion->directory.'/pagos';
    }

    /**
     * Obtener la url de descarga del adjunto
     * 
     * @return string
     */
    public function getDownloadAttribute()
    {
      return route('admin.pago.download', ['pago' => $this->id]);
    }

    /**
     * Obtener la Empresa
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * Obtener la Cotizacion
     */
    public function cotizacion()
    {
      return $this->facturacion->cotizacion();
    }

    /**
     * Obtener la Facturacion
     */
    public function facturacion()
    {
      return $this->belongsTo('App\Facturacion');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function monto()
    {
      return number_format($this->monto, 2, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     *
     * @return string
     */
    public function metodo()
    {
      $metodo = strtolower($this->metodo != 'otro' ? $this->metodo : $this->metodo_otro);
      return ucfirst($metodo);
    }
}
