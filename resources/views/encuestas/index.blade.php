@extends('layouts.app')
@section('title','Encuestas - '.config( 'app.name'))
@section('header','Encuestas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Encuestas</li>
  </ol>
@endsection

@section('content')
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-question-circle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Encuestas</span>
          <span class="info-box-number">{{ count($encuestas) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-question-circle"></i> Encuestas</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('encuestas.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Encuesta</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Usuario</th>
                <th class="text-center">Encuesta</th>
                <th class="text-center">Preguntas</th>
                <th class="text-center">Respuestas</th>
                <th class="text-center">Agregada</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($encuestas as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td class="text-center">
                    <a href="{{route('usuarios.show', ['usuario' => $d->user_id])}}" title="Ver usuario">
                      {{ $d->usuario->nombres }} {{ $d->usuario->apellidos }}
                    </a>
                  </td>
                  <td>{{ $d->titulo }}</td>
                  <td>{{ $d->preguntas()->count() }}</td>
                  <td>{{ $d->respuestas()->groupBy('user_id')->get()->count() }}</td>
                  <td>{{ $d->created_at }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('encuestas.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
