<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventarioEntrega extends model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventarios_entregas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'cantidad',
      'adjunto',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'status' => 'boolean',
    ];

    /**
     * Obtener la fecha del Inventario.
     *
     * @param  string  $value
     * @return datetime
     */
    public function getDirectoryAttribute()
    {
      return $this->inventario->directory().'/entregas/'.$this->id;
    }

    /**
     * Obtener la fecha del Inventario.
     *
     * @param  string  $value
     * @return datetime
     */
    public function getDownloadAttribute()
    {
      return $this->adjunto ? route('entregas.download', ['entrega' => $this->id]) : '#';
    }

    /**
     * Obtener el Inventario al que pertenece
     */
    public function inventario()
    {
      return $this->belongsTo('App\Inventario');
    }

    /**
     * Obtener el Usuario al que le fue entregado el Inventario
     */
    public function entregadoA()
    {
      return $this->belongsTo('App\User', 'entregado', 'id');
    }

    /**
     * Obtener el Usuario que registro la Entrega
     */
    public function realizadoPor()
    {
      return $this->belongsTo('App\User', 'realizado', 'id');
    }

    /**
     * Obtener el atributo formateado
     */
    public function cantidad()
    {
      return number_format($this->cantidad, 0, ',', '.');
    }

    /**
     * Obtener el atributo formateado
     */
    public function recibido()
    {
      return $this->recibido ? '<span class="label label-primary">Recibido</span>' : '<span class="label label-default">Pendiente</span>';
    }
}
