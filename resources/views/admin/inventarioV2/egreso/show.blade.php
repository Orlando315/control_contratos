@extends('layouts.app')

@section('title', 'Egreso de Stock')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Egreso de Stock</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Inventarios V2</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.show', ['inventario' =>  $egreso->inventario_id]) }}">Egreso de Stock</a></li>
        <li class="breadcrumb-item active"><strong>Egreso de Stock</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('inventario-v2-view')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.v2.show', ['inventario' => $egreso->inventario_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('inventario-egreso-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.egreso.edit', ['egreso' => $egreso->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('inventario-egreso-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        @if($egreso->foto)
          <div class="ibox-content no-padding text-center border-left-right">
            <img class="img-fluid" src="{{ $egreso->foto_url }}" alt="Foto" style="max-height: 180px;margin: 0 auto;">
          </div>
        @endif
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Inventario</b>
              <span class="pull-right">
                @permission('inventario-egreso-view')
                  <a href="{{ route('admin.inventario.v2.show', ['inventario' => $egreso->inventario_id]) }}">
                    {{ $egreso->inventario->nombre }}
                  </a>
                @else
                  {{ $egreso->inventario->nombre }}
                @endpermission
              </span>
            </li>
            @if($egreso->isUser())
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  @if($egreso->user)
                    @permission('user-view')
                      <a href="{{ route('admin.usuarios.show', ['usuario' => $egreso->user_id]) }}">
                        {{ $egreso->user->nombre() }}
                      </a>
                    @else
                      {{ $egreso->user->nombre() }}
                    @endpermission
                  @else
                    @nullablestring(null)
                  @endif
                </span>
              </li>
            @endif
            @if($egreso->isCliente())
              <li class="list-group-item">
                <b>Cliente</b>
                <span class="pull-right">
                  @if($egreso->cliente)
                    @permission('cliente-view')
                      <a href="{{ route('admin.cliente.show', ['cliente' => $egreso->cliente_id]) }}">
                        {{ $egreso->cliente->nombre }}
                      </a>
                    @else
                      {{ $egreso->cliente->nombre }}
                    @endpermission
                  @else
                    @nullablestring(null)
                  @endif
                </span>
              </li>
            @endif
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                @if($egreso->contrato)
                  @permission('contrato-view')
                    <a href="{{ route('admin.contratos.show', ['contrato' => $egreso->contrato_id]) }}">
                      {{ $egreso->contrato->nombre }}
                    </a>
                  @else
                    {{ $egreso->contrato->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Faena</b>
              <span class="pull-right">
                @if($egreso->faena)
                  @permission('faena-view')
                    <a href="{{ route('admin.faena.show', ['faena' => $egreso->faena_id]) }}">
                      {{ $egreso->faena->nombre }}
                    </a>
                  @else
                    {{ $egreso->faena->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Centro costo</b>
              <span class="pull-right">
                @if($egreso->centroCosto)
                  @permission('centro-costo-view')
                    <a href="{{ route('admin.centro.show', ['centro' => $egreso->centro_costo_id]) }}">
                      {{ $egreso->centroCosto->nombre }}
                    </a>
                  @else
                    {{ $egreso->centroCosto->nombre }}
                  @endpermission
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Cantidad</b>
              <span class="pull-right">{{ $egreso->cantidad() }}</span>
            </li>
            <li class="list-group-item">
              <b>Costo</b>
              <span class="pull-right">
                @if($egreso->costo)
                  {{ $egreso->costo() }}
                @else
                  @nullablestring(null)
                @endif
              </span>
            </li>
            <li class="list-group-item">
              <b>Descripción</b>
              <span class="pull-right">@nullablestring($egreso->descripcion)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $egreso->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

  @permission('inventario-egreso-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.inventario.egreso.destroy', ['egreso' => $egreso->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Egreso de Stock</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Egreso de Stock?</h4>
              <p class="text-center">La cantidad stock de este Egreso será agregada al Stock disponible del Inventario</p>
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
