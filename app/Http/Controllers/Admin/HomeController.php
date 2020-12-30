<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\{Contrato, Inventario, EmpleadosContrato, Documento, Carpeta};

class HomeController extends Controller
{
    /**
     * Mostrar los contrtos y ducumentos por expirar y vencidos por el tipo y dias proporcionados
     * 
     * @param  string  $type
     * @param  int  $days
     * @return \Illuminate\Http\Response
     */
    public function aboutToExpire($type, $days)
    {
      $days = (int)$days;
      if(!in_array($type, ['contratos', 'empleados', 'transportes']) || $days < 0){
        abort(404);
      }

      if($type == 'contratos'){
        $contratosPorVencer = Contrato::aboutToExpire($days)->get();
        $contratosVencidos = Contrato::expired()->get();
      }

      if($type == 'empleados'){
        $contratosPorVencer = EmpleadosContrato::aboutToExpire($days)->get();
        $contratosVencidos = EmpleadosContrato::expired()->latestPerEmpleado()->get();
      }

      if($type == 'transportes'){
        $contratosPorVencer = null;
        $contratosVencidos = null;
      }

      $model = Carpeta::getModelClass($type);
      $documentosPorVencer = Documento::aboutToExpireByType($model, $days)->get();
      $documentosVencidos = Documento::expiredByType($model)->get();

      return view('admin.expiration', compact(
        'type',
        'days',
        'contratosPorVencer',
        'documentosPorVencer',
        'contratosVencidos',
        'documentosVencidos'
      ));
    }
}
