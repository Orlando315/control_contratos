<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
  protected $fillable = [
    'nombres',
    'representante'
  ];

  public function usuario()
  {
    return $this->hasOne('App\Usuario');
  }

  public function configuracion()
  {
    return $this->hasOne('App\ConfiguracionEmpresa');
  }

  public function contratos()
  {
    return $this->hasMany('App\Contrato');
  }

  public function empleados()
  {
    return $this->hasMany('App\Empleado');
  }

  public function inventarios()
  {
    return $this->hasMany('App\Inventario');
  }

  public function transportes()
  {
    return $this->hasMany('App\Transporte');
  }

  public function anticipos()
  {
    return $this->hasMany('App\Anticipo');
  }

  public function facturas()
  {
    return $this->hasMany('App\Factura');
  }

  public function sueldos()
  {
    return $this->hasMany('App\EmpleadosSueldo');
  }

  public function etiquetas()
  {
    return $this->hasMany('App\Etiqueta');
  }

}
