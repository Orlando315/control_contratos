@extends('layouts.pdf')

@section('title', 'Requerimiento de Materiales')

@section('content')
  <table class="table table-bordered" style="border: none !important">
    <tr>
      <td class="border-0">
        <table class="table table-borderless p-0 w-100">
          <tr>
            <td class="text-left"><img src="{{ Auth::user()->empresa->logo ? Auth::user()->empresa->logo_url : asset('images/logo-small-white.jpg') }}" alt="Logo" style="max-width: 100px"></td>
            <td class="text-right align-bottom"><p class="m-0">{{ date('d-m-Y') }}</p></td>
          </tr>
          <tr>
            <td colspan="2">
              <h4 class="text-center m-0">Egreso de Inventario</h4>
            </td>
          </tr>
        </table>

        <table class="table table-bordered table-sm w-100">
          <tr>
            <td><strong>Inventario:</strong> {{ $egreso->inventario->nombre }}</td>
            <td>
              @if($egreso->isUser())
                <strong>Usuario:</strong>
                @if($egreso->user)
                  {{ $egreso->user->nombre() }}
                @else
                  @nullablestring(null)
                @endif
              @elseif($egreso->isCliente())
                <strong>Cliente:</strong>
                @if($egreso->cliente)
                  {{ $egreso->cliente->nombre }}
                @else
                  @nullablestring(null)
                @endif
              @else
                @nullablestring(null)
              @endif
            </td>
            <td>
              <strong>Contrato:</strong>
              @if($egreso->contrato)
                {{ $egreso->contrato->nombre }}
              @endif
            </td>
            <td>
              <strong>Faena:</strong>
              @if($egreso->faena)
                {{ $egreso->faena->nombre }}
              @else
                @nullablestring(null)
              @endif
            </td>
          </tr>
          <tr>
            <td>
              <strong>Centro de costo:</strong>
              @if($egreso->centroCosto)
                {{ $egreso->centroCosto->nombre }}
              @else
                @nullablestring(null)
              @endif
            </td>
            <td>
              <strong>Cantidad:</strong>
              {{ $egreso->cantidad() }}
            </td>
            <td>
              <strong>Costo: </strong>
              @if($egreso->costo)
                {{ $egreso->costo() }}
              @else
                @nullablestring(null)
              @endif
            </td>
            <td>
              <strong>Descripción: </strong>
              @nullablestring($egreso->descripcion)
            </td>
          </tr>
          <tr>
            <td>
              <strong>Realizado: </strong>
              {{ $egreso->created_at->format('d-m-Y') }}
            </td>
            <td>
              @if($egreso->isUser())
                <strong>Recibido:</strong>
                {{ $egreso->recibido(true) }}

                @if($egreso->recibido)
                  | {{ $egreso->recibido->format('d-m-Y') }}
                @endif
              @endif
            </td>
            <td></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
@endsection
