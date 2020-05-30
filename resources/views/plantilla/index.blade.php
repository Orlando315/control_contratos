@extends('layouts.app')
@section('title','Plantillas - '.config('app.name'))
@section('header', 'Plantillas')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Plantillas</li>
  </ol>
@endsection

@section('content')
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-object-group"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Plantillas</span>
          <span class="info-box-number">{{ count($plantillas) }}</span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-cube"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Variables</span>
          <span class="info-box-number">{{ count($variables) }}</span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-object-group"></i> Plantillas</a></li>
          <li><a href="#tab_2" data-toggle="tab"><i class="fa fa-cube"></i> Variables</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <h3 class="box-title text-right m-0" style="margin-bottom: 10px">
              <a class="btn btn-success btn-flat" href="{{ route('plantilla.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Plantilla</a>
            </h3>
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Secciones</th>
                  <th class="text-center">Documentos</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($plantillas as $d)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->nombre }}</td>
                    <td>{{ $d->secciones_count }}</td>
                    <td>{{ $d->documentos_count }}</td>
                    <td>
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('plantilla.show', ['plantilla' => $d->id] )}}"><i class="fa fa-search"></i></a>
                      <a class="btn btn-success btn-flat btn-sm" href="{{ route('plantilla.edit', ['plantilla' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div><!-- /.tab-pane -->
          <div class="tab-pane" id="tab_2">
            <h3 class="box-title text-right m-0" style="margin-bottom: 10px">
              <a class="btn btn-success btn-flat" href="{{ route('variable.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Variable</a>
            </h3>
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Tipo</th>
                  <th class="text-center">Variable</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($variables as $v)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $v->nombre }}</td>
                    <td>{{ $v->tipo() }}</td>
                    <td>{{ $v->variable }}</td>
                    <td>
                      <a class="btn btn-success btn-flat btn-sm" href="{{ route('variable.edit', ['plantilla' => $v->id] )}}"><i class="fa fa-pencil"></i></a>
                      <button class="btn btn-danger btn-flat btn-sm" data-toggle="modal" data-target="#delModal" data-variable="{{ $v->id }}"><i class="fa fa-times" aria-hidden="true"></i></button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div>
    </div>
  </div>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar variable</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delete-form" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}

              <h4 class="text-center">¿Esta seguro de eliminar esta Variable?</h4><br>

              <center>
                <button class="btn btn-flat btn-danger" type="submit">Eliminar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    $(document).ready(function(){
      $('#delModal').on('show.bs.modal', function(e){
        var btn = $(e.relatedTarget),
            variable = btn.data('variable'),
            action = '{{ route("variable.index") }}/' + variable;

        if(!variable){
          return false;
        }

        $('#delete-form').attr('action', action);
      });
    });
  </script>
@endsection
