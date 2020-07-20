@extends('layouts.app')

@section('title', 'Transporte')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Transportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventarios.index') }}">Transportes</a></li>
        <li class="breadcrumb-item active"><strong>Transporte</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('admin.transportes.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      @if(Auth::user()->tipo <= 2)
        <a class="btn btn-default btn-sm" href="{{ route('admin.transportes.edit', ['transporte' => $transporte->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      @endif
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              <b>Supervisor</b>
              <span class="pull-right">
                <a href="{{ route('admin.usuarios.show', ['usuario' => $transporte->user_id]) }}">
                  {{ $transporte->usuario->nombres }} {{ $transporte->usuario->apellidos }}
                </a>
              </span>
            </li>
            <li class="list-group-item">
              <b>Vehículo</b>
              <span class="pull-right">{{ $transporte->vehiculo }}</span>
            </li>
            <li class="list-group-item">
              <b>Patente</b>
              <span class="pull-right">{{ $transporte->patente }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $transporte->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9">
      <div class="ibox mb-3">
        <div class="ibox-title">
          <h5>Adjuntos</h5>

          @if($transporte->documentos->count() < 10)
            <div class="ibox-tools">
              <a class="btn btn-warning btn-xs" href="{{ route('admin.carpeta.create', ['type' => 'transportes', 'id' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Carpeta</a>
              <a class="btn btn-primary btn-xs" href="{{ route('admin.documentos.create', ['type' => 'transportes', 'id' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Adjunto</a>
            </div>
          @endif
        </div>
        <div class="ibox-content">
          <div class="row icons-box icons-folder">
            @foreach($transporte->carpetas()->main()->get() as $carpeta)
              <div class="col-md-3 col-xs-4 infont mb-3">
                <a href="{{ route('admin.carpeta.show', ['carpeta' => $carpeta->id]) }}">
                  <i class="fa fa-folder" aria-hidden="true"></i>
                  <p class="m-0">{{ $carpeta->nombre }}</p>
                </a>
              </div>
            @endforeach
          </div>

          <hr class="hr-line-dashed">

          <div class="row">
            @forelse($transporte->documentos()->main()->get() as $documento)
              @include('partials.documentos', ['edit' => true])
            @empty
              <div class="col-12">
                <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <div class="tabs-container">
        <ul class="nav nav-tabs">
          <li><a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="fa fa-clipboard"></i> Contratos</a></li>
          <li><a class="nav-link" href="#tab-2" data-toggle="tab"><i class="fa fa-file-text-o"></i> Consumos</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
              <div class="mb-3">
                <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus" aria-hidden="true"></i> Agregar a Contrato</button>
              </div>
              <table class="table table-bordered data-table table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Agregado</th>
                    @if(Auth::user()->tipo <= 2)
                      <th class="text-center">Acción</th>
                    @endif
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($transporte->contratos as $d)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $d->contrato->nombre }} </td>
                      <td>{{ $d->created_at }} </td>
                      @if(Auth::user()->tipo <= 2)
                        <td>
                          <button class="btn btn-danger btn-xs" data-url="{{ route('admin.transportes.contratos.destroy', ['contrato' => $d->id]) }}" data-toggle="modal" data-target="#delContratoModal"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div id="tab-2" class="tab-pane">
            <div class="panel-body">
              <div class="mb-3">
                <a class="btn btn-primary btn-xs" href="{{ route('admin.consumos.create', ['transporte' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Consumo</a>
              </div>
              <table id="tableConsumos" class="table table-bordered data-table table-hover table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Contrato</th>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($transporte->consumos as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>
                        <a href="{{ route('admin.contratos.show', ['contrato' => $d->contrato_id]) }}">
                          {{ $d->contrato->nombre }}
                        </a>
                      </td>
                      <td>{{ $d->tipo() }} </td>
                      <td>{{ $d->fecha() }} </td>
                      <td>{{ $d->valor() }}</td>
                      <td>
                        <a class="btn btn-success btn-sm" href="{{ route('admin.consumos.show', ['consumo' => $d->id]) }}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(Auth::user()->tipo <= 2)
    <div id="addModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.transportes.contratos.store', ['transporte' => $transporte->id]) }}" method="POST">
            {{ csrf_field() }}
            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="addModalLabel">Agregar a contrato</h4>
            </div>
            <div id="add-modal-body" class="modal-body">
              <div class="form-group">
                <label for="contrato">Contrato: *</label>
                <select id="contrato" class="form-control" name="contrato" required style="width: 100%">
                  <option value="">Seleccione...</option>
                  @foreach($contratos as $contrato)
                    <option value="{{ $contrato->id }}">{{ $contrato->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="delContratoModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delContratoModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="destroyContrato" action="#" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
              </button>
              <h4 class="modal-title" id="delContratoModalLabel">Eliminar Contrato</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Contrato?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="delModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form action="{{ route('admin.transportes.destroy', ['transporte' => $transporte->id]) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="delModalLabel">Eliminar Transporte</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro de eliminar este Transporte?</h4>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  <div id="delFileModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="delete-file-form" action="#" method="POST">
          {{ method_field('DELETE') }}
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="delFileModalLabel">Eliminar Adjunto</h4>
          </div>
          <div class="modal-body">
            <h4 class="text-center">¿Esta seguro de eliminar este Adjunto?</h4>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#contrato').select2({
        dropdownParent: $('#add-modal-body'),
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#delContratoModal').on('show.bs.modal', function(e){
        let btn = $(e.relatedTarget),
            action = btn.data('url');

        if(!action){ return false; }

        $('#destroyContrato').attr('action', action)
      })

      $('#delFileModal').on('show.bs.modal', function(e){
        let button = $(e.relatedTarget),
            action = button.data('url');

        $('#delete-file-form').attr('action', action);
      });

      $('#delete-file-form').submit(deleteFile);
    });

    function deleteFile(e){
      e.preventDefault();

      let form = $(this),
          action = form.attr('action');

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){
          $('#adjunto-' + r.id).remove();
          $('#delFileModal').modal('hide');
        }else{
          $('.alert').show().delay(7000).hide('slow');
        }
      })
      .fail(function(){
        $('.alert').show().delay(7000).hide('slow');
      })
    }
  </script>
@endsection
