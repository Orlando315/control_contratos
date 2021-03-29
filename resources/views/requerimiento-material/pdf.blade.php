@extends('layouts.pdf')

@section('title', 'Requerimiento de Materiales')

@section('content')
  <table class="table table-bordered" style="border: none !important">
    <tr>
      <td class="border-0">
        <table class="table table-borderless p-0 w-100">
          <tr>
            <td class="text-left"><img src="{{ Auth::user()->empresa->logo ? Auth::user()->empresa->logo_url : asset('images/logo-small-white.jpg') }}" alt="Logo" style="max-width: 100px"></td>
            <td class="text-right align-bottom"><h2 class="m-0">RM - {{ $requerimiento->id }}</h2></td>
          </tr>
        </table>

        <table class="table table-bordered table-sm w-100">
          <tr>
            <td><strong>Contrato:</strong> {{ $requerimiento->contrato->nombre }}</td>
            <td>
              <strong>Faena:</strong>
              @if($requerimiento->faena)
                {{ $requerimiento->faena->nombre }}
              @else
                @nullablestring(null)
              @endif
            </td>
            <td>
              <strong>Centro de costo:</strong>
              @if($requerimiento->centroCosto)
                {{ $requerimiento->centroCosto->nombre }}
              @else
                @nullablestring(null)
              @endif
            </td>
            <td><strong>Requerido para:</strong> {{ optional($requerimiento->created_at)->format('d-m-Y') }}</td>
          </tr>
          <tr>
            <td><strong>Solicitante:</strong> {{ $requerimiento->userSolicitante->nombre() }}</td>
            <td><strong>Dirigido a:</strong> {{ $requerimiento->dirigidoA->nombre() }}</td>
            <td><strong>Urgencia: </strong> {!! $requerimiento->urgencia(true) !!}</td>
            <td><strong>Estatus: </strong> {!! $requerimiento->status(true) !!}</td>
          </tr>
        </table>

        <table class="table table-bordered table-sm w-100">
          <thead>
            <tr class="light-background">
              <th colspan="3" class="text-center">Productos</th>
            </tr>
            <tr class="light-background">
              <th class="text-center">#</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            @foreach($requerimiento->productos as $producto)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $producto->nombre }}</td>
                <td class="text-right">{{ $producto->cantidad() }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <table class="table table-bordered table-sm w-100">
          @foreach ($requerimiento->firmantes->chunk(3) as $firmantes)
            <tr>
              @foreach ($firmantes as $firmante)
                <td class="p-0">
                  <table class="table table-bordered table-sm w-100 m-0">
                    <thead>
                      <tr class="light-background">
                        <td class="text-center">
                          {{ $firmante->pivot->texto }}
                        </td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="align-bottom" style="height: 50px">
                          <h5 class="text-center m-0">
                            {{ $firmante->nombre() }}
                          </h5>        
                        </td>
                      </tr>
                    </tbody>
                  </table>                  
                </td>
              @endforeach
            </tr>
          @endforeach
        </table>
      </td>
    </tr>
  </table>
@endsection
