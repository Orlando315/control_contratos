@extends('layouts.pdf')

@section('title', 'Orden de compra')

@section('content')
  <table class="table table-bordered" style="border: none !important">
    <tr>
      <td class="border-0">
        <table class="table table-borderless p-0 w-100">
          <tr>
            <td class="text-left"><img src="{{ Auth::user()->empresa->logo ? Auth::user()->empresa->logo_url : asset('images/logo-small-white.jpg') }}" alt="Logo" style="max-width: 100px"></td>
            <td class="text-right align-bottom"><h2 class="m-0">{{ $compra->codigo() }}</h2></td>
          </tr>
        </table>

        <h2 class="text-center m-0">Orden de compra</h2>

        @if($compra->hasRequerimiento())
          <table class="table table-bordered table-sm w-100">
            <tr>
              <td colspan="4"><strong>Requerimiento:</strong> RM - {{ $compra->requerimiento->id }}</td>
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
              <td><strong>Requerido para:</strong> {{ optional($compra->requerimiento->created_at)->format('d-m-Y') }}</td>
            </tr>
            <tr>
              <td><strong>Solicitante:</strong> {{ $compra->requerimiento->userSolicitante->nombre() }}</td>
              <td><strong>Dirigido a:</strong> {{ $compra->requerimiento->dirigidoA->nombre() }}</td>
              <td><strong>Urgencia: </strong> {!! $compra->requerimiento->urgencia(true) !!}</td>
              <td><strong>Estatus: </strong> {!! $compra->requerimiento->status(true) !!}</td>
            </tr>
          </table>
        @endif

        <table class="table table-bordered table-sm w-100">
          <tr>
            <td><strong>Generado por:</strong> {{ $compra->user->nombre() }}</td>
            <td><strong>Proveedor:</strong> {{ $compra->proveedor->nombre }}</td>
          </tr>
          <tr>
            <td colspan="2"><strong>Notas:</strong> @nullablestring($compra->notas)</td>
          </tr>
        </table>

        <table class="table table-bordered table-sm w-100">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Tipo</br>código</th>
              <th class="text-center">Código</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Cantidad</th>
              <th class="text-center">Precio</th>
              <th class="text-center">IVA</th>
              <th class="text-center">Total</th>
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
                <td class="text-right">{{ $producto->precio() }}</td>
                <td class="text-right">{{ $producto->impuesto() }}</td>
                <td class="text-right">{{ $producto->total() }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </td>
    </tr>
  </table>
@endsection
