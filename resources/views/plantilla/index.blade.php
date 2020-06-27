@extends('layouts.app')

@section('title', 'Plantillas')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Plantillas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><strong>Plantillas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Plantillas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-object-group text-warning"></i> {{ count($plantillas) }}</h2>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Variables</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-cube text-warning"></i> {{ count($variables) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-object-group"></i> Plantillas</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-cube"></i> Variables</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <div class="mb-3">
                <a class="btn btn-primary btn-sm" href="{{ route('plantilla.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Plantilla</a>
              </div>
              <table class="table data-table table-bordered table-hover table-sm w-100">
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
                        <a class="btn btn-success btn-xs" href="{{ route('plantilla.show', ['plantilla' => $d->id] )}}"><i class="fa fa-search"></i></a>
                        <a class="btn btn-primary btn-xs" href="{{ route('plantilla.edit', ['plantilla' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3">
                <a class="btn btn-primary btn-sm" href="{{ route('variable.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Variable</a>
              </div>
              <table class="table data-table table-bordered table-hover table-sm w-100">
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
                        <a class="btn btn-primary btn-xs" href="{{ route('variable.edit', ['plantilla' => $v->id] )}}"><i class="fa fa-pencil"></i></a>
                        <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delModal" data-variable="{{ $v->id }}"><i class="fa fa-times" aria-hidden="true"></i></button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div><!-- /.tab-pane -->
        </div><!-- /.tab-content -->
      </div>
    </div>
  </div>

  <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="delete-form" action="#" method="POST">
          {{ method_field('DELETE') }}

          <div class="modal-header">           
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delModalLabel">Eliminar variable</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar esta Variable?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
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
