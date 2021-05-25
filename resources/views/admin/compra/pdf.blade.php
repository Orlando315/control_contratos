@extends('layouts.pdf')

@section('title', 'Orden de compra')

@section('content')
  <table class="table table-bordered" style="border: none !important">
    <tr>
      <td class="border-0">
        <table class="table table-borderless p-0 w-100">
          <tr>
            <td class="text-left"><img src="{{ Auth::user()->empresa->logo ? Auth::user()->empresa->logo_url : asset('images/logo-small-white.jpg') }}" alt="Logo" style="max-width: 100px"></td>
            <td class="text-right align-bottom">
              <h2 class="text-center m-0">ORDEN DE COMPRA</h2>
              <p class="text-center m-0">({{ $compra->codigo() }})</p>
            </td>
          </tr>
        </table>

        <table class="table table-bordered table-sm w-100">
          <tr>
            <td><strong>Proveedor:</strong> {{ $compra->proveedor->nombre }}</td>
            <td><strong>Fecha:</strong> {{ optional($compra->created_at)->format('d-m-Y') }}</td>
          </tr>
          <tr>
            <td><strong>Contacto:</strong> @nullablestring(optional($compra->contacto)->nombre)</td>
            <td><strong>Teléfono:</strong> @nullablestring(optional($compra->contacto)->telefono)</td>
          </tr>
          <tr>
            <td><strong>Email:</strong> @nullablestring(optional($compra->contacto)->email)</td>
            <td></td>
          </tr>
        </table>

        <table class="table table-bordered table-sm w-100">
          <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Tipo<br>código</th>
              <th>Código</th>
              <th>Nombre</th>
              <th>Cantidad</th>
              <th>Unidad</th>
              <th>Precio<br>unitario</th>
              <th>IVA</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            @foreach($compra->productos as $producto)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>@nullablestring($producto->tipo_codigo)</td>
                <td>@nullablestring($producto->codigo)</td>
                <td>
                  {{ $producto->nombre }}
                  @if($producto->descripcion)
                    <p class="m-0"><small>{{ $producto->descripcion }}</small></p>
                  @endif
                </td>
                <td class="text-right">{{ $producto->cantidad() }}</td>
                <td class="text-center">
                  @if($producto->inventario)
                    {{ $producto->inventario->unidad->nombre }}
                  @else
                    @nullablestring(null)
                  @endif
                </td>
                <td class="text-right">{{ $producto->precio() }}</td>
                <td class="text-right">{{ $producto->precio() }}</td>
                <td class="text-right">{{ $producto->total() }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr class="text-right">
              <td colspan="8"><strong>TOTAL:</strong></td>
              <td>{{ $compra->total() }}</td>
            </tr>
          </tfoot>
        </table>

        @if($compra->hasRequerimiento())
          <table class="table table-bordered table-sm w-100">
            <tbody>
              <tr>
                <td colspan="3" class="text-center border-0">
                  <h2 class="m-0">REQUERIMIENTO DE MATERIALES</h2>
                  <p class="m-0">({{ $compra->requerimiento->id() }})</p>
                </td>
              </tr>
              <tr>
                <td><strong>Contrato:</strong> {{ $compra->requerimiento->contrato->nombre }}</td>
                <td>
                  <strong>Faena:</strong>
                  @if($compra->requerimiento->faena)
                    {{ $compra->requerimiento->faena->nombre }}
                  @else
                    @nullablestring(null)
                  @endif
                </td>
                <td>
                  <strong>Centro de costo:</strong>
                  @if($compra->requerimiento->centroCosto)
                    {{ $compra->requerimiento->centroCosto->nombre }}
                  @else
                    @nullablestring(null)
                  @endif
                </td>
              </tr>
              <tr>
                <td><strong>Solicitante:</strong> {{ $compra->requerimiento->userSolicitante->nombre() }}</td>
                <td><strong>Dirigido a:</strong> {{ $compra->requerimiento->dirigidoA->nombre() }}</td>
                <td><strong>Urgencia: </strong> {!! $compra->requerimiento->urgencia(true) !!}</td>
              </tr>
              <tr>
                <td><strong>Requerido para el: </strong> @nullablestring(optional($compra->requerimiento->fecha)->format('d-m-Y'))</td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        @endif

        <p><strong>Notas:</strong> {{ $compra->notas }}</p>
      </td>
    </tr>
  </table>
@endsection
