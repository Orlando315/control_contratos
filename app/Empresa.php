<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'empresas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombres',
      'representante'
    ];

    /**
     * Obtener el path del Logo, si no tiene un logo se carga la imagen por defecto
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
      $logo = $this->logo ? 'storage/'.$this->logo : 'images/default.jpg';
      return asset($logo);
    }

    /**
     * Obtener el path del directorio donde se guardaran los archivos
     * 
     * @return string
     */
    public function getDirectoryAttribute()
    {
      return 'Empresa'.$this->id;
    }

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
     * Obtener las Faenas
     */
    public function faenas()
    {
      return $this->hasMany('App\Faena');
    }

    /**
     * Obtener los Clientes
     */
    public function clientes()
    {
      return $this->hasMany('App\Cliente');
    }

    /**
     * Obtener los Proveedores
     */
    public function proveedores()
    {
      return $this->hasMany('App\Proveedor');
    }

    /**
     * Obtener los Cotizaciones
     */
    public function cotizaciones()
    {
      return $this->hasMany('App\Cotizacion');
    }

    /**
     * Obtener los Facturaciones
     */
    public function facturaciones()
    {
      return $this->hasMany('App\Facturacion');
    }

    /**
     * Obtener una parte especifica del rut
     *
     * @param  int  $part
     * @return string
     */
    public function getRutPart($part)
    {
      return explode('-', $this->usuario->rut)[$part];
    }

    /**
     * Obtener solo el rut
     * 
     * @return string
     */
    public function getRut()
    {
      return $this->getRutPart(0);
    }

    /**
     * Obtener el digito validador del rut
     * 
     * @return string
     */
    public function getRutDv()
    {
      return $this->getRutPart(1);
    }
}
