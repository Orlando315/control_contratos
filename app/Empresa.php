<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Covid19Respuesta;

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
      'rut',
      'nombre',
      'representante',
      'logo',
      'telefono',
      'email',
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
      'configuracion'
    ];
    
    /**
     * User con role Empresa
     *
     * @var array
     */
    private $_user = null;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
      parent::boot();
      /**
       * Eliminar toda la informacion relacionada
       */
      static::deleting(function ($model) {
        $model->users()->delete();
        
        if(Storage::exists($model->directory)){
          Storage::deleteDirectory($model->directory);
        }
      });
    }

    /**
     * Obtener el User con Role Empresa
     *
     * @param  \App\Models\Empresa|null
     */
    public function getUserAttribute()
    {
      return $this->_user = $this->_user ?? $this->users()->whereRoleIs('empresa')->first();
    }

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
     * Obtener los user que pertenecen a la empresa
     */
    public function users()
    {
      return $this->belongsToMany('App\User', 'empresa_user');
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
     * Obtener las Facturaciones
     */
    public function facturaciones()
    {
      return $this->hasMany('App\Facturacion');
    }

    /**
     * Obtener los Pagos
     */
    public function pagos()
    {
      return $this->hasMany('App\Pago');
    }

    /**
     * Obtener los Ordenes de compra
     */
    public function compras()
    {
      return $this->hasMany('App\OrdenCompra');
    }

    /**
     * Obtener las respuestas de la encuesta Covid-19
     */
    public function covid19Respuestas()
    {
      return $this->hasMany('App\Covid19Respuesta');
    }

    /**
     * Obtener los Centros de costos
     */
    public function centros()
    {
      return $this->hasMany('App\CentroCosto');
    }

    /**
     * Obtener una parte especifica del rut
     *
     * @param  int  $part
     * @return string
     */
    public function getRutPart($part)
    {
      return explode('-', $this->rut)[$part];
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
