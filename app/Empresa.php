<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $fillable = [
      'nombres',
      'representante'
    ];

    /**
     * Obtener el User de la Empresa
     */
    public function usuario()
    {
      return $this->hasOne('App\Usuario');
    }

    /**
     * Obtener la Configuracion
     */
    public function configuracion()
    {
      return $this->hasOne('App\ConfiguracionEmpresa');
    }

    /**
     * Obtener los Contratos
     */
    public function contratos()
    {
      return $this->hasMany('App\Contrato');
    }

    /**
     * Obtener los Empleados
     */
    public function empleados()
    {
      return $this->hasMany('App\Empleado');
    }

    /**
     * Obtener los Inventarios
     */
    public function inventarios()
    {
      return $this->hasMany('App\Inventario');
    }

    /**
     * Obtener los Transportes
     */
    public function transportes()
    {
      return $this->hasMany('App\Transporte');
    }

    /**
     * Obtener los Anticipos
     */
    public function anticipos()
    {
      return $this->hasMany('App\Anticipo');
    }

    /**
     * Obtener las Facturas
     */
    public function facturas()
    {
      return $this->hasMany('App\Factura');
    }

    /**
     * Obtener los Sueldos
     */
    public function sueldos()
    {
      return $this->hasMany('App\EmpleadosSueldo');
    }

    /**
     * Obtener las Etiquetas
     */
    public function etiquetas()
    {
      return $this->hasMany('App\Etiqueta');
    }

    /**
     * Obtener los Gastos
     */
    public function gastos()
    {
      return $this->hasMany('App\Gasto');
    }

    /**
     * Obtener las Variables de las Plantillas.
     */
    public function variables()
    {
      return $this->hasMany('App\PlantillaVariable');
    }

    /**
     * Obtener las Plantillas
     */
    public function plantillas()
    {
      return $this->hasMany('App\Plantilla');
    }

    /**
     * Obtener las PlantillaDocumento
     */
    public function documentos()
    {
      return $this->hasMany('App\PlantillaDocumento');
    }

    /**
     * Obtener el path del Logo, si no tiene un logo se carga la imagen por defecto
     */
    public function getLogoUrlAttribute()
    {
      $logo = $this->logo ? 'storage/'.$this->logo : 'images/default.jpg';
      return asset($logo);
    }

    /**
     * Obtener el path del directorio donde se guardaran los archivos
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->id;
    }
}
