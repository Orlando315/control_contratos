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
      $facturas = $contrato->facturas()->select('tipo', 'nombre', 'valor', 'fecha', 'pago_estado')
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

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'empleados' => $contrato->empleados()->count(),
          'total' => $contratoAlcanceLiquido
        ];

        foreach ($contrato->empleados()->with('usuario')->get() as $empleado) {
          $empleadoAlcanceLiquido = $empleado->sueldos()
                                            ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('alcance_liquido');

          $dataEmpleados[] = [
            'contrato' => $contrato->nombre,
            'rut' => $empleado->usuario->rut,
            'empleado' => $empleado->usuario->nombres . " " . $empleado->usuario->apellidos,
            'total' => $empleadoAlcanceLiquido
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

    public function transportesIndex(){
      $contratos = Contrato::all();
      return view('reportes.transportes', ['contratos' => $contratos]);
    }

    public function transportesGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? Contrato::findOrFail($request->contrato) : Contrato::all();
      $dataContratos = $dataTransportes = [];

      foreach ($contratos as $contrato) {

        $contratoMantenimiento = $contrato->transportesConsumos()
                                            ->where('tipo', 1)
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('valor');

        $contratoCombustible = $contrato->transportesConsumos()
                                            ->where('tipo', 2)
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('valor');

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'transportes' => $contrato->transportes()->count(),
          'mantenimiento' => $contratoMantenimiento,
          'combustible' => $contratoCombustible,
          'total' => $contratoMantenimiento + $contratoCombustible
        ];

        foreach ($contrato->transportes()->get() as $transporte) {

          $transporteMantenimiento = $transporte->consumos()
                                              ->where([
                                                ['tipo', 1],
                                                ['contrato_id', $contrato->id]
                                              ])
                                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                              ->sum('valor');

          $transporteCombustible = $transporte->consumos()
                                              ->where([
                                                ['tipo', 2],
                                                ['contrato_id', $contrato->id]
                                              ])
                                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                              ->sum('valor');

          $dataTransportes[] = [
            'contrato' => $contrato->nombre,
            'transporte' => $transporte->vehiculo,
            'mantenimiento' => $transporteMantenimiento,
            'combustible' => $transporteCombustible,
            'total' => $transporteMantenimiento + $transporteCombustible
          ];
        }
      }

      return ['contratos' => $dataContratos, 'transportes' => $dataTransportes];
    }

    public function comidasIndex(){
      $contratos = Contrato::all();
      return view('reportes.comidas', ['contratos' => $contratos]);
    }

    public function comidasGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? Contrato::findOrFail($request->contrato) : Contrato::all();
      $dataContratos = $dataEmpleados = [];

      foreach ($contratos as $contrato) {

        $contratoComidas = 0;

        foreach ($contrato->empleados()->with('usuario')->get() as $empleado) {
          $comidas = $empleado->eventos()
                                        ->where([
                                          ['tipo', 1],
                                          ['comida', true],
                                          ['pago', true]
                                        ])
                                        ->whereBetween('inicio', [$inicio->toDateString(), $fin->toDateString()])
                                        ->count();

          $dataEmpleados[] = [
            'contrato' => $contrato->nombre,
            'rut' => $empleado->usuario->rut,
            'empleado' => $empleado->usuario->nombres . " " . $empleado->usuario->apellidos,
            'comidas' => $comidas,
            'total' => $comidas * 500
          ];

          $contratoComidas += $comidas;
        }


        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'empleados' => $contrato->empleados()->count(),
          'comidas' => $contratoComidas,
          'total' => $contratoComidas * 500
        ];
      }

      return ['contratos' => $dataContratos, 'empleados' => $dataEmpleados];
    }

    public function reemplazosIndex(){
      $contratos = Contrato::all();
      return view('reportes.reemplazos', ['contratos' => $contratos]);
    }

    public function reemplazosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? Contrato::findOrFail($request->contrato) : Contrato::all();
      $dataContratos = $dataEmpleados = [];

      foreach ($contratos as $contrato) {

        $contratoTotal = $reemplazosTotal = 0;

        foreach ($contrato->empleados()->with('usuario')->get() as $empleado) {
          $reemplazos = $empleado->reemplazos()
                                  ->whereBetween('inicio', [$inicio->toDateString(), $fin->toDateString()])
                                  ->count();

          $total = $empleado->reemplazos()
                              ->whereBetween('inicio', [$inicio->toDateString(), $fin->toDateString()])
                              ->sum('valor');

          $dataEmpleados[] = [
            'contrato' => $contrato->nombre,
            'rut' => $empleado->usuario->rut,
            'empleado' => $empleado->usuario->nombres . " " . $empleado->usuario->apellidos,
            'reemplazos' => $reemplazos,
            'total' => $total
          ];

          $reemplazosTotal += $reemplazos;
          $contratoTotal += $total;
        }


        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'empleados' => $contrato->empleados()->count(),
          'reemplazos' => $reemplazosTotal,
          'total' => $contratoTotal
        ];
      }

      return ['contratos' => $dataContratos, 'empleados' => $dataEmpleados];
    }



    public function generalIndex(){
      $contratos = Contrato::all();
      return view('reportes.general', ['contratos' => $contratos]);
    }

    public function generalGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? Contrato::findOrFail($request->contrato) : Contrato::all();
      $dataContratos = [];

      foreach ($contratos as $contrato) {

        $facturasIngresos = $contrato->facturas()
                              ->where('tipo', 1)
                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                              ->sum('valor');

        $facturasEgresos = $contrato->facturas()
                              ->where('tipo', 2)
                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                              ->sum('valor');

        $anticipos = $contrato->anticipos()
                                      ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                      ->sum('anticipo');

        $sueldoAlcanceLiquido = $contrato->sueldos()
                                            ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('alcance_liquido');

        $comidas = $contrato->empleadosEventos()
                                      ->where(function($query) use ($inicio, $fin){
                                        $query->where([
                                          ['tipo', 1],
                                          ['comida', true],
                                          ['pago', true]
                                          ])
                                          ->whereBetween('inicio', [$inicio->toDateString(), $fin->toDateString()]);
                                      })
                                      ->count() * 500;

        $transporteMantenimiento = $contrato->transportesConsumos()
                                            ->where('tipo', 1)
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('valor');

        $transporteCombustible = $contrato->transportesConsumos()
                                            ->where('tipo', 2)
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('valor');

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'ingresos' => $facturasIngresos,
          'egresos' => $facturasEgresos,
          'anticipos' => $anticipos,
          'sueldos' => $sueldoAlcanceLiquido,
          'comidas' => $comidas,
          'transporte' => $transporteMantenimiento + $transporteCombustible,
          'total' => $facturasIngresos - ($facturasEgresos + $anticipos + $sueldoAlcanceLiquido + $comidas + $transporteMantenimiento + $transporteCombustible)
        ];
      }

      return $dataContratos;
    }

}


?>
