@extends('layouts.app')

@section('title', 'Gasto')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Gastos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.gasto.index') }}">Gastos</a></li>
        <li class="breadcrumb-item active"><strong>Gasto</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      @permission('gasto-index')
        <a class="btn btn-default btn-sm" href="{{ route('admin.gasto.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @endpermission
      @permission('gasto-edit')
        <a class="btn btn-default btn-sm" href="{{ route('admin.gasto.edit', ['gasto' => $gasto->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      @endpermission
      @permission('gasto-delete')
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endpermission
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Contrato</b>
              <span class="pull-right">
                @permission('contrato-view')
                  <a href="{{ route('admin.contrato.show', ['contrato' => $gasto->contrato_id]) }}">{{ $gasto->contrato->nombre }}</a>
                @else
                  {{ $gasto->contrato->nombre }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Etiqueta</b>
              <span class="pull-right">
                @permission('etiqueta-view')
                  <a href="{{ route('admin.etiqueta.show', ['etiqueta' => $gasto->etiqueta_id]) }}">{{ $gasto->etiqueta->etiqueta }}</a>
                @else
                  {{ $gasto->etiqueta->etiqueta }}
                @endpermission
              </span>
            </li>
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $gasto->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Valor</b>
              <span class="pull-right">{{ $gasto->valor() }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $gasto->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div><!-- .row -->

  @permission('gasto-delete')
    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.gasto.destroy', ['gasto' => $gasto->id]) }}" method="POST">
            @method('DELETE')
            @csrf

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Gasto</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Gasto?</h4>
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
