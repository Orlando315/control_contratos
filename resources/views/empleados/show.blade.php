@extends( 'layouts.app' )

@section( 'title', 'Empleado - '.config( 'app.name' ) )
@section( 'header', 'Empleado' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('contratos.show', ['contrato' => $empleado->contrato_id]) }}">Empleados</a></li>
	  <li class="active"> Empleado </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('contratos.show', ['contrato' => $empleado->contrato_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('empleados.edit', [$empleado->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <a class="btn btn-flat btn-warning" href="{{ route('empleados.cambio', [$empleado->id]) }}"><i class="fa fa-refresh" aria-hidden="true"></i> Cambio de jornada</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    <button class="btn btn-flat bg-purple" data-toggle="modal" data-target="#toggleModal">
      <i class="fa fa-exchange" aria-hidden="true"></i>
      {{ $empleado->usuario->tipo == 3 ? 'Volver Empleado' : 'Ascender a Supervisor' }}
    </button>
    <button class="btn btn-flat bg-navy" data-toggle="modal" data-target="#contratoModal">
      <i class="fa fa-refresh" aria-hidden="true"></i> Cambio de contrato
    </button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del Empleado
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right"><a href="{{ route('contratos.show', ['contrato' => $empleado->contrato_id]) }}">{{ $empleado->contrato->nombre }}</a></span>
              </li>
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">
                  <a href="{{ route('usuarios.show', ['usuario' => $empleado->usuario->id]) }}">
                    {{ $empleado->usuario->usuario }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Nombres</b>
                <span class="pull-right">{{ $empleado->usuario->nombres }}</span>
              </li>
              <li class="list-group-item">
                <b>Apellidos</b>
                <span class="pull-right">{{ $empleado->usuario->apellidos }}</span>
              </li>
              <li class="list-group-item">
                <b>Sexo</b>
                <span class="pull-right">{{ $empleado->sexo }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha de nacimiento</b>
                <span class="pull-right">{{ $empleado->fecha_nacimiento }}</span>
              </li>
              <li class="list-group-item">
                <b>RUT</b>
                <span class="pull-right"> {{ $empleado->usuario->rut }} </span>
              </li>
              <li class="list-group-item">
                <b>Dirección</b>
                <span class="pull-right"> {{ $empleado->direccion }} </span>
              </li>
              <li class="list-group-item">
                <b>Teléfono</b>
                <span class="pull-right"> {{ $empleado->usuario->telefono }} </span>
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <span class="pull-right">{{ $empleado->usuario->email }}</span>
              </li>
              <li class="list-group-item">
                <b>Talla de camisa</b>
                <span class="pull-right">{{ $empleado->talla_camisa }}</span>
              </li>
              <li class="list-group-item">
                <b>Talla de zapato</b>
                <span class="pull-right">{{ $empleado->talla_zapato }}</span>
              </li>
              <li class="list-group-item">
                <b>Talla de pantalon</b>
                <span class="pull-right">{{ $empleado->talla_pantalon }}</span>
              </li>
              <li class="list-group-item">
                <b>Registrado</b>
                <span class="pull-right">{{ $empleado->created_at }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos Bancarios
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Banco</b>
                <span class="pull-right">{{ $empleado->banco->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Tipo de cuenta</b>
                <span class="pull-right">{{ $empleado->banco->tipo_cuenta }}</span>
              </li>
              <li class="list-group-item">
                <b>Cuenta</b>
                <span class="pull-right"> {{ $empleado->banco->cuenta }} </span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Contrato
              <span class="pull-right">
                <button class="btn btn-sm btn-flat btn-default" titl="Ver historial" data-toggle="modal" data-target="#historyModal">
                  <i class="fa fa-list"></i>
                </button>
              </span>
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Jornada</b>
                <span class="pull-right">{{ $empleado->contratos->last()->jornada }}</span>
              </li>
              <li class="list-group-item">
                <b>Sueldo</b>
                <span class="pull-right">{{ number_format($empleado->contratos->last()->sueldo, 0, ',', '.') }}</span>
              </li>
              <li class="list-group-item">
                <b>Inicio</b>
                <span class="pull-right">{{ $empleado->contratos->last()->inicio }}</span>
              </li>
              <li class="list-group-item">
                <b>Inicio de Jornada</b>
                <span class="pull-right"> {{$empleado->contratos->last()->inicio_jornada}} </span>
              </li>
              <li class="list-group-item">
                <b>Fin</b>
                <span class="pull-right"> {!! $empleado->contratos->last()->fin ? $empleado->contratos->last()->fin : '<span class="text-muted">Indefinido</span>' !!} </span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-6">
        <div class="col-md-12" style="margin-bottom: 5px">
          <h4>
            Documentos
            @if($empleado->documentos()->count() < 10)
            <span class="pull-right">
              <a class="btn btn-flat btn-success btn-sm" href="{{ route('documentos.createEmpleado', ['empleado' => $empleado->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a>
            </span>
            @endif
          </h4>
        </div>
        @foreach($empleado->documentos()->get() as $documento)
          <div id='file-{{$documento->id}}' class='col-md-6 col-sm-6 col-xs-12'>
            {!! $documento->generateThumb() !!}
          </div>
        @endforeach
      </div>    
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-level-up"></i> Anticipos</h3>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Anticipo</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($empleado->anticipos as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $d->fecha }}</td>
                    <td>{{ $d->anticipo() }}</td>
                    <td>
                      <a class="btn btn-primary btn-flat btn-sm" href="{{ route('anticipos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-retweet"></i> Reemplazos</h3>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Reemplazó a</th>
                  <th class="text-center">Valor</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($empleado->reemplazos()->get() as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $d->inicio }}</td>
                    <td>{!! $d->nombreReemplazo() !!}</td>
                    <td>{{ $d->valor() }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="col-md-12" style="padding:0">
        <div class="col-md-6">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-money"></i> Sueldos</h3>
            </div>
            <div class="box-body">
              <table class="table data-table table-bordered table-hover" style="width: 100%">
                <thead>
                  <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Alcance líquido</th>
                  <th class="text-center">Sueldo líquido</th>
                  <th class="text-center">Acción</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->sueldos()->get() as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $d->created_at }}</td>
                      <td>{{ $d->alcanceLiquido() }}</td>
                      <td>{{ $d->sueldoLiquido() }}</td>
                      <td>
                        <a class="btn btn-primary btn-flat btn-sm" href="{{ route('sueldos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Entregas de Inventario</h3>
            </div>
            <div class="box-body">
              <table class="table data-table table-bordered table-hover" style="width: 100%">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Realizado por</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Recibido</th>
                  </tr>
                </thead>
                <tbody class="text-center">
                  @foreach($empleado->entregas()->get() as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td><a href="{{ route('inventarios.show', ['inventario' => $d->inventario_id]) }}">{{ $d->inventario->nombre }}</a></td>
                      <td>{{ $d->realizadoPor->nombres }} {{ $d->realizadoPor->apellidos }}</td>
                      <td>{{ $d->cantidad() }}</td>
                      <td>{{ $d->created_at }}</td>
                      <td>{!! $d->recibido() !!}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-flat btn-success" data-toggle="modal" data-target="#exportModal"><i class="fa fa-file-excel-o"></i> Exportar a excel</button>
              </div>
              <div class="col-md-12">
                <div id="calendar"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div id="toggleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="toggleModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="toggleModalLabel">Cambiar de nivel</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('empleados.toggleTipo', ['empleado' => $empleado->id]) }}" method="POST">
              {{ method_field('PATCH') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de cambiar a {{ $empleado->usuario->tipo == 3 ? 'Empleado' : 'Supervisor' }}?</h4><br>

              <center>
                <button class="btn btn-flat btn-success" type="submit">Guardar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="contratoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="contratoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="contratoModalLabel">Cambiar de contrato</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('empleados.cambioContrato', ['empleado' => $empleado->id]) }}" method="POST">
              {{ method_field('PATCH') }}
              {{ csrf_field() }}

              <div class="form-group {{ $errors->has('contrato') ? 'has-error' : '' }}">
                <label class="control-label" for="contrato">Contrato: *</label>
                <select id="contrato" class="form-control" name="contrato" required>
                  <option value="">Seleccione...</option>
                  @foreach($contratos as $contrato)
                    @if($contrato->id != $empleado->contrato->id)
                    <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
                    @endif
                  @endforeach
                </select>
              </div>

              <center>
                <button class="btn btn-flat btn-success" type="submit">Guardar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="historyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="historyModalLabel">Historial de contratos</h4>
        </div>
        <div class="modal-body">
          @foreach($empleado->contratos()->get() as $contrato)
            <ul class="list-group">
              <li class="list-group-item">
                <b>Creado</b>
                <span class="pull-right">{{ $contrato->created_at }}</span>
              </li>
              <li class="list-group-item">
                <b>Jornada</b>
                <span class="pull-right">{{ $contrato->jornada }}</span>
              </li>
              <li class="list-group-item">
                <b>Sueldo</b>
                <span class="pull-right">{{ number_format($contrato->sueldo, 0, ',', '.') }}</span>
              </li>
              <li class="list-group-item">
                <b>Inicio</b>
                <span class="pull-right">{{ $contrato->inicio }}</span>
              </li>
              <li class="list-group-item">
                <b>Inicio de Jornada</b>
                <span class="pull-right"> {{$contrato->inicio_jornada}} </span>
              </li>
              <li class="list-group-item">
                <b>Fin</b>
                <span class="pull-right"> {{ $contrato->fin }}
              </li>
            </ul>
          @endforeach
        </div>
      </div>
    </div>
  </div>

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

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Empleado</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('empleados.destroy', [$empleado->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Empleado?</h4><br>

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

  <div id="delEventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delEventModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delEventModalLabel">Eliminar Evento</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delEventForm" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Desea eliminar este evento?</h4><br>

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

  <div id="eventsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Agregar evento</h4>
        </div>
        <div class="modal-body">
          <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-danger" style="display: none">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong class="text-center">Ha ocurrido un error.</strong> 
            </div>
          </div>
            <form id="eventForm" class="col-md-8 col-md-offset-2" action="{{ route('eventos.store', ['empleado'=>$empleado->id]) }}" method="POST">
              <input id="eventDay" type="hidden" name="inicio" value="">
              {{ csrf_field() }}
              <h4 class="text-center" id="eventTitle"></h4>
              <div class="form-group">
                <label for="tipo">Evento: *</label>
                <select id="tipo" class="form-control" name="tipo" required>
                  <option value="">Seleccione...</option>
                  <option value="2">Licencia médica</option>
                  <option value="3">Vacaciones</option>
                  <option value="4">Permiso</option>
                  <option value="5">Permiso no remunerable</option>
                  @if(!$empleado->despidoORenuncia())
                    <option value="6">Despido</option>
                    <option value="7">Renuncia</option>
                  @endif
                  <option value="8">Inasistencia</option>
                  <option value="9">Reemplazo</option>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label" for="fin">Fin: <span class="help-block">(Opcional)</span></label>
                <input id="fin" class="form-control" type="text" name="fin" placeholder="yyyy-mm-dd">
              </div>

              <div class="form-group {{ $errors->has('reemplazo') ? 'has-error' : '' }}" hidden>
                <label class="control-label" for="reemplazo">Reemplazo: *</label>
                <select id="reemplazo" class="form-control" name="reemplazo" required style="width: 100%">
                  <option value="">Seleccione...</option>
                  @foreach($empleados as $d)
                    <option value="{{ $d->id }}">{{ $d->usuario->rut }} | {{ $d->usuario->nombres }} {{ $d->usuario->apellidos }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-group" hidden>
                <label class="control-label" for="valor">Valor: *</label>
                <input id="valor" class="form-control" type="number" step="1" min="1" max="999999999" name="valor" placeholder="Valor" rqeuired>
              </div>

              <center>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-flat btn-primary" type="submit">Gardar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="exportModalLabel">Exportar a excel</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('empleados.export', [$empleado->id]) }}" method="POST">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label" for="inicioExport">Inicio: *</label>
                <input id="inicioExport" class="form-control" type="text" name="inicio" placeholder="yyyy-mm-dd" required>
              </div>

              <div class="form-group">
                <label class="control-label" for="finExport">Fin: *</label>
                <input id="finExport" class="form-control" type="text" name="fin" placeholder="yyyy-mm-dd" rqeuired>
              </div>

              <center>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-flat btn-success" type="submit">Enviar</button>
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
    var jornada = @json($empleado->proyectarJornada()),
        eventos = @json($empleado->getEventos()),
        feriados = @json($empleado->getFeriados());
   	
    $(document).ready(function(){
      $('#delFileModal').on('show.bs.modal', function(e){
        var button = $(e.relatedTarget),
            file   = button.data('file'),
            action = '{{ route("documentos.index") }}/' + file;

        $('#delete-file-form').attr('action', action);
      });

      $('#fin').datepicker({
        format: 'yyyy-mm-dd',
        startDate: 'today',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#inicioExport, #finExport').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false,
        autoclose: true
      }).on('changeDate', function(e){
        var inicio = new Date($('#inicioExport').val()),
            fin = new Date($('#finExport').val());

        if(inicio > fin){
          inicio.setDate(inicio.getDate() + 1)
          var newDate = inicio.getFullYear()+'-'+(inicio.getMonth()+1)+'-'+inicio.getDate()
          $('#finExport').datepicker('setDate', newDate)
        }
      });

      $('#delete-file-form').submit(deleteFile);
      $('#eventForm').submit(storeEvent)
      $('#delEventForm').submit(delEvent)

      $('#calendar').fullCalendar({
        locale: 'es',
        eventSources: [{
          events: jornada.trabajo,
          color: '#00a65a',
          textcolor: 'white'
        },
        {
          events: jornada.descanso,
          color: '#9c9c9c',
          textcolor: 'white'
        },
        {
          events: feriados,
          color: '#f39c12',
          textcolor: 'white'
        },
        {
          events: eventos,
        }],
        dayClick: function(date){
          $('#eventTitle').text(date.format())
          $('#eventDay').val(date.format())
          $('#eventsModal').modal('show')
        },
        eventClick: function(event){
          if(event.id){
            $('#delEventModal').modal('show');
            $('#delEventForm').attr('action', '{{ route("eventos.index") }}/' + event.id);
          }else{
            $('#delEventForm').attr('action', '#');
          }
        }
      })


      $('#tipo').change(function(){
        let tipo = $(this).val()

        let isReemplazo = tipo == 9
        let isDespidoRenuncia = (tipo == 6 || tipo == 7)

        $('#fin')
          .closest('.form-group')
          .attr('hidden', (isReemplazo || isDespidoRenuncia))

        $('#reemplazo, #valor')
          .prop('required', isReemplazo)
          .closest('.form-group')
          .attr('hidden', !isReemplazo)

      })

      $('#reemplazo').select2()
      $('#reemplazo').change()

   	});//Ready

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

    function storeEvent(e){
      e.preventDefault();

      var form = $(this),
          action = form.attr('action'),
          alert  = $('#eventsModal .alert');
          button = form.find('button[type="submit"]');

      button.button('loading');
      alert.hide();

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){

          if(r.evento.tipo == 6 || r.evento.tipo == 7 || r.evento.tipo == 9){
            location.reload()
          }

          $('#calendar').fullCalendar('renderEvent', {
            id: r.evento.id,
            className: 'clickableEvent',
            title: r.data.titulo,
            start: r.evento.inicio,
            end: r.evento.fin,
            allDay: true,
            color: r.data.color
          });
          form[0].reset()
          $('#eventsModal').modal('hide');
        }else{
          alert.show().delay(7000).hide('slow');
          alert.find('strong').text(r.message || 'Ha ocurrido un error.')
        }
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
        alert.find('strong').text('Ha ocurrido un error')
      })
      .always(function(){
        button.button('reset');
      })
    }

    function delEvent(e){
      e.preventDefault();

      var form = $(this),
          action = form.attr('action'),
          alert  = form.find('.alert');
          button = form.find('button[type="submit"]');

      button.button('loading');
      alert.hide();

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(r){
        if(r.response){
          $('#calendar').fullCalendar('removeEvents', r.evento.id);
          $('#delEventModal').modal('hide');
        }else{
          alert.show().delay(7000).hide('slow');
        }
      })
      .fail(function(){
        alert.show().delay(7000).hide('slow');
      })
      .always(function(){
        button.button('reset');
      })
    }
 	</script>
@endsection
