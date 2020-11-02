<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\{Inventario, Factura, Contrato};
use Carbon\Carbon;

class ReportesController extends Controller
{
    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function inventariosIndex(){
      return view('admin.reportes.inventarios');
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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

    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function facturasIndex(){
      $contratos = Contrato::all();

      return view('admin.reportes.facturas', compact('contratos'));
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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

    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function eventosIndex(){
      $contratos = Contrato::all();
      return view('admin.reportes.eventos', compact('contratos'));
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function eventosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);
      $contrato = Contrato::findOrFail($request->contrato);

      $data = $contrato->getAllEventsData($inicio->toDateString(), $fin->toDateString());

      return $data;
    }

    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function sueldosIndex(){
      $contratos = Contrato::all();
      return view('admin.reportes.sueldos', compact('contratos'));
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function sueldosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? [Contrato::findOrFail($request->contrato)] : Contrato::all();
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

    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function anticiposIndex(){
      $contratos = Contrato::all();
      return view('admin.reportes.anticipos', ['contratos' => $contratos]);
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function anticiposGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? [Contrato::findOrFail($request->contrato)] : Contrato::all();
      $dataContratos = $dataEmpleados = [];

      foreach ($contratos as $contrato) {

        $contratoAnticipo = $contrato->anticipos()
                                      ->aprobados()
                                      ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                      ->sum('anticipo');

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'empleados' => $contrato->empleados()->count(),
          'total' => $contratoAnticipo
        ];

        foreach ($contrato->empleados()->with('usuario')->get() as $empleado) {
          $empleadoAnticipo = $empleado->anticipos()
                                        ->aprobados()
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

    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function transportesIndex(){
      $contratos = Contrato::all();

      return view('admin.reportes.transportes', compact('contratos'));
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function transportesGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? [Contrato::findOrFail($request->contrato)] : Contrato::all();
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

        $contratoPeaje = $contrato->transportesConsumos()
                                            ->where('tipo', 3)
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('valor');

        $contratoGastosVarios = $contrato->transportesConsumos()
                                            ->where('tipo', 4)
                                            ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                            ->sum('valor');

        $dataContratos[] = [
          'contrato' => $contrato->nombre,
          'transportes' => $contrato->transportes()->count(),
          'mantenimiento' => $contratoMantenimiento,
          'combustible' => $contratoCombustible,
          'peaje' => $contratoPeaje,
          'gastos' => $contratoGastosVarios,
          'total' => $contratoMantenimiento + $contratoCombustible + $contratoPeaje + $contratoGastosVarios
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

          $transportePeaje = $transporte->consumos()
                                              ->where([
                                                ['tipo', 3],
                                                ['contrato_id', $contrato->id]
                                              ])
                                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                              ->sum('valor');

          $transporteGastosVarios = $transporte->consumos()
                                              ->where([
                                                ['tipo', 4],
                                                ['contrato_id', $contrato->id]
                                              ])
                                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                                              ->sum('valor');

          $dataTransportes[] = [
            'contrato' => $contrato->nombre,
            'transporte' => $transporte->vehiculo,
            'mantenimiento' => $transporteMantenimiento,
            'combustible' => $transporteCombustible,
            'peaje' => $transportePeaje,
            'gastos' => $transporteGastosVarios,
            'total' => $transporteMantenimiento + $transporteCombustible + $transportePeaje + $transporteGastosVarios
          ];
        }
      }

      return ['contratos' => $dataContratos, 'transportes' => $dataTransportes];
    }

    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function reemplazosIndex(){
      $contratos = Contrato::all();

      return view('admin.reportes.reemplazos', compact('contratos'));
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function reemplazosGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? [Contrato::findOrFail($request->contrato)] : Contrato::all();
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



    /**
     * Formulario de consulta.
     *
     * @return \Illuminate\Http\Response
     */
    public function generalIndex(){
      $contratos = Contrato::all();

      return view('admin.reportes.general', compact('contratos'));
    }

    /**
     * Obtener la informacion con los parametros especificados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function generalGet(Request $request)
    {
      $inicio = new Carbon($request->inicio);
      $fin    = new Carbon($request->fin);

      $contratos = isset($request->contrato) ? [Contrato::findOrFail($request->contrato)] : Contrato::all();
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
                              ->aprobados()
                              ->whereBetween('fecha', [$inicio->toDateString(), $fin->toDateString()])
                              ->sum('anticipo');

        $sueldoAlcanceLiquido = $contrato->sueldos()
                                        ->whereBetween('created_at', [$inicio->toDateString(), $fin->toDateString()])
                                        ->sum('alcance_liquido');

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
          'transporte' => $transporteMantenimiento + $transporteCombustible,
          'total' => $facturasIngresos - ($facturasEgresos + $anticipos + $sueldoAlcanceLiquido + $transporteMantenimiento + $transporteCombustible)
        ];
      }

      return $dataContratos;
    }
}
