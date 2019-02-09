<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Inventario;
use App\Factura;
use App\Contrato;
use Carbon\Carbon;

class ReportesController extends Controller{
    public function inventariosIndex(){
      return view('reportes.inventarios');
    }

    public function inventariosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);
      $inventarios = Inventario::select('tipo', 'nombre', 'valor', 'fecha', 'cantidad', 'created_at')
                                ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])->get();

      foreach ($inventarios as $i => $inventario) {
        $inventario->tipo = $inventario->tipo();
        $inventarios[$i] = $inventario;
      }

      return $inventarios;
    }

    public function facturasIndex(){
      $contratos = Contrato::all();
      return view('reportes.facturas', ['contratos' => $contratos]);
    }

    public function facturasGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);
      $contrato = Contrato::findOrFail($request->contrato);
      $facturas = Factura::select('tipo', 'nombre', 'valor', 'fecha', 'pago_estado')
                                ->where('contrato_id', $contrato->id)
                                ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])->get();

      foreach ($facturas as $i => $factura) {
        $factura->tipolabel = $factura->tipo();
        $factura->pago = $factura->pago();
        $facturas[$i] = $factura;
      }

      return $facturas;
    }

    public function eventosIndex(){
      $contratos = Contrato::all();
      return view('reportes.eventos', ['contratos' => $contratos]);
    }

    public function eventosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);
      $contrato = Contrato::findOrFail($request->contrato);

      $data = $contrato->getAllEventsData($inicio->toDateString(), $fin->toDateString());

      return $data;
    }

    public function sueldosIndex(){
      $contratos = Contrato::all();
      return view('reportes.sueldos', ['contratos' => $contratos]);
    }

    public function sueldosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? Contrato::findOrFail($request->contrato) : Contrato::all();
      $dataContratos = $dataEmpleados = [];

      foreach ($contratos as $contrato) {

        $contratoAlcanceLiquido = $contrato->sueldos()
                                            ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('alcance_liquido');
        $contratoBonoReemplazo  = $contrato->sueldos()
                                            ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('bono_reemplazo');

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'empleados' => $contrato->empleados()->count(),
          'total' => $contratoAlcanceLiquido + $contratoBonoReemplazo
        ];

        foreach ($contrato->empleados()->with('usuario')->get() as $empleado) {
          $empleadoAlcanceLiquido = $empleado->sueldos()
                                            ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('alcance_liquido');
          $empleadoBonoReemplazo  = $empleado->sueldos()
                                            ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('bono_reemplazo');

          $dataEmpleados[] = [
            'contrato' => $contrato->nombre,
            'rut' => $empleado->usuario->rut,
            'empleado' => $empleado->usuario->nombres . " " . $empleado->usuario->apellidos,
            'total' => $empleadoAlcanceLiquido + $empleadoBonoReemplazo
          ];
        }
      }

      return ['contratos' => $dataContratos, 'empleados' => $dataEmpleados];
    }

    public function anticiposIndex(){
      $contratos = Contrato::all();
      return view('reportes.anticipos', ['contratos' => $contratos]);
    }

    public function anticiposGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? Contrato::findOrFail($request->contrato) : Contrato::all();
      $dataContratos = $dataEmpleados = [];

      foreach ($contratos as $contrato) {

        $contratoAnticipo = $contrato->anticipos()
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('anticipo');

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'empleados' => $contrato->empleados()->count(),
          'total' => $contratoAnticipo
        ];

        foreach ($contrato->empleados()->with('usuario')->get() as $empleado) {
          $empleadoAnticipo = $empleado->anticipos()
                                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                              ->sum('anticipo');

          $dataEmpleados[] = [
            'contrato' => $contrato->nombre,
            'rut' => $empleado->usuario->rut,
            'empleado' => $empleado->usuario->nombres . " " . $empleado->usuario->apellidos,
            'total' => $empleadoAnticipo
          ];
        }
      }

      return ['contratos' => $dataContratos, 'empleados' => $dataEmpleados];
    }
}


?>
