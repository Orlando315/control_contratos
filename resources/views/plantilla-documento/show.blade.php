@extends('layouts.app')

@section('title', 'Documento - '.config('app.name'))
@section('header', 'Documento')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('plantilla.documento.index') }}">Documentos</a></li>
	  <li class="active"> Documento </li>
	</ol>
@endsection

@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('plantilla.documento.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    @if(Auth::user()->tipo < 2)
      <a class="btn btn-flat btn-success" href="{{ route('plantilla.documento.edit', ['documento' => $documento->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    @endif
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-warning">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del documento
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nombre</b>
                <span class="pull-right">{{ $documento->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  <a href="{{ route('contratos.show', ['contrato' => $documento->contrato_id]) }}">
                    {{ $documento->contrato->nombre }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Empleado</b>
                <span class="pull-right">
                  <a href="{{ route('empleados.show', ['empleado' => $documento->empleado_id]) }}">
                    {{ $documento->empleado->nombre() }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Plantilla</b>
                <span class="pull-right">
                  <a href="{{ route('plantilla.show', ['plantilla' => $documento->plantilla_id]) }}">
                    {{ $documento->plantilla->nombre }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Padre</b>
                <span class="pull-right">
                  @if($documento->padre)
                    <a href="{{ route('plantilla.documento.show', ['plantilla' => $documento->documento_id]) }}">
                      {{ $documento->padre->nombre }}
                    </a>
                  @else
                    N/A
                  @endif
                </span>
              </li>
              <li class="list-group-item">
                <b>Caducidad</b>
                <span class="pull-right">{{ $documento->caducidad ? $documento->caducidad->format('d-m-Y') : 'N/A' }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
          <div class="box-footer text-center text-muted">
            {{ $documento->created_at->format('d-m-Y H:i:s') }}
          </div>
        </div>
      </div>
      <div class="col-md-9">
        @foreach($documento->plantilla->secciones as $seccion)
          <div class="box box-secondary">
            <div class="box-header">
              <h6 class="box-title">Sección #{{ $loop->iteration }}: {!! $seccion->nombre ?? '<span class="text-muted">N/A</span>' !!}</h6>
            </div>
            <div class="box-body box-profile">
              {!! $documento->fillSeccionVariables($seccion) !!}
            </div><!-- /.box-body -->
          </div>
        @endforeach
      </div>
    </div>
  </section>

  @if(Auth::user()->tipo < 2)
    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Documento</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form class="col-md-10 col-md-offset-1" action="{{ route('plantilla.documento.destroy', ['documento' => $documento->id]) }}" method="POST">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}

                <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4>
                <p class="text-center">Esta acción no se puede deshacer</p>

                <div class="text-center mt-2">
                  <button class="btn btn-flat btn-danger" type="submit">Eliminar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </div class="text-center mt-2">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif
@endsection
