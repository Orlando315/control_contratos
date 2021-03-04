@extends('layouts.pdf')

@section('title', 'Requerimiento de Materiales')

@section('content')
  <h2 class="mb-3 text-center">Requerimiento de Materiales</h2>

  <table class="table table-borderless p-0 w-100" style="position: relative;">
    <tr style="position: relative;">
      <td class="p-0" style="width:30%">
        <ul class="list-group" style="display: block">
          <li class="list-group-item p-2">
            <b>Solicitante</b>
            <span class="pull-right"> 
              {{ $requerimiento->userSolicitante->nombre() }}
            </span>
          </li>
          <li class="list-group-item p-2">
            <b>Dirigido a: </b> {{ $requerimiento->dirigidoA->nombre() }}
          </li>
          <li class="list-group-item p-2">
            <b>Contrato</b> {{ $requerimiento->contrato->nombre }}
          </li>
          <li class="list-group-item p-2">
            <b>Faena:</b>
            @if($requerimiento->faena)
              {{ $requerimiento->faena->nombre }}
            @else
              @nullablestring(null)
            @endif
          </li>
          <li class="list-group-item p-2">
            <b>Centro costo:</b>
            @if($requerimiento->centroCosto)
              {{ $requerimiento->centroCosto->nombre }}
            @else
              @nullablestring(null)
            @endif
          </li>
          <li class="list-group-item p-2">
            <b>Fecha:</b> {{ $requerimiento->created_at->format('d-m-Y') }}
          </li>
          <li class="list-group-item p-2">
            <b>Estatus:</b> {!! $requerimiento->status() !!}
          </li>
        </ul>
      </td>
      <td class="p-0" style="width: 70%;position: relative;">
        @foreach ($requerimiento->firmantes->chunk(3) as $firmantes)
          <div class="row" style="position: relative;">
            @foreach ($firmantes as $firmante)
              <div class="pdf-col-4" style="position: relative;">
                <ul class="list-group" style="display: block">
                  <li class="list-group-item p-2 light-background">
                    <h4 class="m-0">{{ $firmante->pivot->texto }}</h4>
                  </li>
                  <li class="list-group-item p-2">
                    <p class="mb-1" style="position: relative;">
                      {{ $firmante->nombre() }}
                      @if($firmante->pivot->isObligatorio())
                        <span class="text-danger">*</span>
                      @endif
                    </p>
                    <p class="m-0">{!! $firmante->pivot->status() !!}</p>
                  </li>
                </ul>
              </div>
            @endforeach
          </div>
        @endforeach
      </td>
    </tr>
  </table>

  <table class="table table-bordered table-sm w-100">
    <thead>
      <tr class="light-background">
        <th class="text-center">Nombre</th>
        <th class="text-center">Cantidad</th>
      </tr>
    </thead>
    <tbody>
      @foreach($requerimiento->productos as $producto)
        <tr>
          <td>{{ $producto->nombre }}</td>
          <td class="text-right">{{ $producto->cantidad() }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
