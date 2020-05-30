@extends( 'layouts.app' )

@section( 'title', 'Contrato - '.config( 'app.name' ) )
@section( 'header', 'Contrato' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('contratos.index') }}">Contratos</a></li>
	  <li class="active"> Contrato </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('contratos.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    @if(Auth::user()->tipo < 2)
      <a class="btn btn-flat btn-success" href="{{ route('contratos.edit', [$contrato->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    @endif
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del contrato
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nombre</b>
                <span class="pull-right">{{ $contrato->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Inicio</b>
                <span class="pull-right">{{ $contrato->inicio }}</span>
              </li>
              <li class="list-group-item">
                <b>Fin</b>
                <span class="pull-right"> {{ $contrato->fin }} </span>
              </li>
              <li class="list-group-item">
                <b>Valor</b>
                <span class="pull-right"> {{ $contrato->valor() }} </span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="tab-success active"><a href="#tab_11" data-toggle="tab"><i class="fa fa-paperclip"></i> Adjuntos</a></li>
            <li class="tab-warning"><a href="#tab_12" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documentos</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_11">
              <div class="row">
                <div class="col-md-12" style="margin-bottom: 5px">
                  <h4>
                    Documentos
                    @if($contrato->documentos->count() < 10)
                    <span class="pull-right">
                      <a class="btn btn-flat btn-success btn-sm" href="{{ route('documentos.createContrato', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a>
                    </span>
                    @endif
                  </h4>
                </div>
                @forelse($contrato->documentos as $documento)
                  <div id='file-{{$documento->id}}' class='col-md-4 col-sm-6 col-xs-12'>
                    {!! $documento->generateThumb() !!}
                  </div>
                @empty
                <div class="col-12">
                  <h4 class="text-center text-muted">No hay documetos adjuntos</h4>
                </div>
                @endforelse
              </div>
            </div>
            <div class="tab-pane" id="tab_12">
              <div class="box-hedaer">
                <span class="pull-right">
                  <a class="btn btn-success btn-flat" href="{{ route('plantilla.documento.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo documento</a>
                </span>
              </div>
              <div class="box-body">
                <table class="table data-table table-bordered table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Documento</th>
                      <th class="text-center">Empleado</th>
                      <th class="text-center">Padre</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->plantillaDocumentos as $d)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nombre }}</td>
                        <td>{{ $d->empleado->nombre() }}</td>
                        <td>{{ $d->padre ? $d->padre->nombre : 'N/A' }}</td>
                        <td>
                          <a class="btn btn-primary btn-flat btn-sm" href="{{ route('plantilla.documento.show', ['documento' => $d->id] )}}"><i class="fa fa-search"></i></a>
                          <a class="btn btn-success btn-flat btn-sm" href="{{ route('plantilla.documento.edit', ['documento' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
    
    <div class="row">
      <div class="col-md-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="tab-danger active"><a href="#tab_21" data-toggle="tab"><i class="fa fa-address-card"></i> Empleados</a></li>
            <li class="tab-primary"><a href="#tab_22" data-toggle="tab"><i class="fa fa-car"></i> Transportes</a></li>
            <li class="tab-danger"><a href="#tab_23" data-toggle="tab"><i class="fa fa-arrow-right"></i> Entregas de Inventarios</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_21">
              <div class="box-header with-border">
                <!--
                <a class="btn btn-danger btn-flat" href="{{ route('eventos.events', ['contrato' => $contrato->id]) }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Total de eventos</a>
                -->
                <a class="btn btn-success btn-flat" href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}"><i class="fa fa-money" aria-hidden="true"></i> Ver sueldos</a>
                <a class="btn btn-warning btn-flat" href="{{ route('contratos.comidas', ['contrato' => $contrato->id]) }}"><i class="fa fa-cutlery" aria-hidden="true"></i> Ver comidas</a>
                <a class="btn bg-purple btn-flat" href="{{ route('contratos.calendar', ['contrato' => $contrato->id]) }}"><i class="fa fa-calendar" aria-hidden="true"></i> Ver calendario</a>
                <a class="btn btn-success btn-flat" href="{{ route('empleados.create', ['contrato' => $contrato->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo empleado</a>
              </div>
              <div class="box-body">
                <table class="table data-table table-bordered table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombres</th>
                      <th class="text-center">Apellidos</th>
                      <th class="text-center">RUT</th>
                      <th class="text-center">Teléfono</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->empleados as $d)
                      <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $d->usuario->nombres }}</td>
                        <td>{{ $d->usuario->apellidos }}</td>
                        <td>{{ $d->usuario->rut }}</td>
                        <td>{{ $d->usuario->telefono }}</td>
                        <td>
                          <a class="btn btn-primary btn-flat btn-sm" href="{{ route( 'empleados.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                          <a class="btn btn-success btn-flat btn-sm" href="{{ route( 'empleados.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- #tab_1 -->
            <div class="tab-pane" id="tab_22">
              <div class="box-body">
                <table class="table data-table table-bordered table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Supervisor</th>
                      <th class="text-center">Vehiculo</th>
                      <th class="text-center">Patente</th>
                      <th class="text-center">Agregado</th>
                      <th class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->transportes as $d)
                      <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>
                          <a href="{{ route('usuarios.show', ['usuario' => $d->user_id]) }}">
                            {{ $d->usuario->nombres }} {{ $d->usuario->apellidos }}
                          </a>
                        </td>
                        <td>{{ $d->vehiculo }}</td>
                        <td>{{ $d->patente }}</td>
                        <td>{{ $d->created_at }}</td>
                        <td>
                          <a class="btn btn-primary btn-flat btn-sm" href="{{ route('transportes.show', ['id' => $d->transporte_id] )}}"><i class="fa fa-search"></i></a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- #tab_2 -->
            <div class="tab-pane" id="tab_23">
              <div class="box-body">
                <table class="table data-table table-bordered table-hover" style="width: 100%">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">Nombre</th>
                      <th class="text-center">Realizado por</th>
                      <th class="text-center">Entregado a</th>
                      <th class="text-center">Cantidad</th>
                      <th class="text-center">Fecha</th>
                      <th class="text-center">Recibido</th>
                    </tr>
                  </thead>
                  <tbody class="text-center">
                    @foreach($contrato->entregas()->get() as $d)
                      <tr>
                        <td>{{ $loop->index + 1 }}</td>
                          <td><a href="{{ route('inventarios.show', ['inventario' => $d->inventario->id]) }}">{{ $d->inventario->nombre }}</a></td>
                        <td>{{ $d->realizadoPor->nombres }} {{ $d->realizadoPor->apellidos }}</td>
                        <td>{{ $d->nombres }} {{ $d->apellidos }}</td>
                        <td>{{ $d->cantidad() }}</td>
                        <td>{{ $d->created_at }}</td>
                        <td>{!! $d->recibido() !!}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div><!-- #tab_3 -->
          </div>
        </div>
      </div>
    </div>
  </section>

  <div id="delFileModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delFileModalLabel">Eliminar archivo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delete-file-form" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Documento?</h4><br>

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

  @if(Auth::user()->tipo < 2)
    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel">Eliminar Contrato</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form class="col-md-8 col-md-offset-2" action="{{ route('contratos.destroy', [$contrato->id]) }}" method="POST">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <h4 class="text-center">¿Esta seguro de eliminar este Contrato?</h4><br>
                <p class="text-center">Se eliminaran todos los empleados en este contrato</p>

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
  @endif
@endsection

@section('scripts')
  <script type="text/javascript">

    $(document).ready(function(){
      $('#delFileModal').on('show.bs.modal', function(e){
        var button = $(e.relatedTarget),
            file   = button.data('file'),
            action = '{{ route("documentos.index") }}/' + file;

        $('#delete-file-form').attr('action', action);
      });

      $('#delete-file-form').submit(deleteFile);
    });

    function deleteFile(e){
      e.preventDefault();

      var form = $(this),
          action = form.attr('action');

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){
          $('#file-' + r.id).remove();
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
