@extends('layouts.app')

@section('title', 'Ingreso de Stock')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Ingreso de Stock</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Inventarios V2</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.show', ['inventario' =>  $ingreso->inventario_id]) }}">Ingreso de Stock</a></li>
        <li class="breadcrumb-item active"><strong>Ingreso de Stock</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('inventario-v2-view')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.v2.show', ['inventario' => $ingreso->inventario_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('inventario-ingreso-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.ingreso.edit', ['ingreso' => $ingreso->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('inventario-ingreso-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        @if($ingreso->foto)
          <div class="ibox-content no-padding text-center border-left-right">
            <img class="img-fluid" src="{{ $ingreso->foto_url }}" alt="Foto" style="max-height: 180px;margin: 0 auto;">
          </div>
        @endif
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Inventario</b>
              <span class="pull-right">
                @permission('inventario-ingreso-view')
                  <a href="{{ route('admin.inventario.v2.show', ['inventario' => $ingreso->inventario_id]) }}">
                    {{ $ingreso->inventario->nombre }}
                  </a>
                @else
                  {{ $ingreso->inventario->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Proveedor</b>
              <span class="pull-right">
                @if($ingreso->proveedor)
                  @permission('proveedor-view')
                    <a href="{{ route('admin.proveedor.show', ['proveedor' => $ingreso->proveedor_id]) }}">
                      {{ $ingreso->proveedor->nombre }}
                    </a>
                  @else
                    {{ $ingreso->proveedor->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Cantidad</b>
              <span class="pull-right">{{ $ingreso->cantidad() }}</span>
            </li>
            <li class="list-group-item">
              <b>Costo</b>
              <span class="pull-right">
                @if($ingreso->costo)
                  {{ $ingreso->costo() }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($ingreso->descripcion)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $ingreso->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  @permission('inventario-ingreso-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.inventario.ingreso.destroy', ['ingreso' => $ingreso->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Ingreso de Stock</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Ingreso de Stock?</h4>
              <p class="text-center">La cantidad stock de este Ingreso será reducida del Stock disponible del Inventario</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection
