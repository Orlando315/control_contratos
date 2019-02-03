@extends( 'layouts.app' )

@section( 'title', 'Transporte - '.config( 'app.name' ) )
@section( 'header', 'Transporte' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('transportes.index') }}">Transportes</a></li>
	  <li class="active"> Transporte </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('transportes.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    @if(Auth::user()->tipo <= 2)
    <a class="btn btn-flat btn-success" href="{{ route('transportes.edit', [$transporte->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus" aria-hidden="true"></i> Agregar a contrato</button>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
    @endif
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del transporte
            </h4>
            <p class="text-muted text-center">{{ $transporte->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Supervisor</b>
                <span class="pull-right">
                  <a href="{{ route('usuarios.show', ['usuario' => $transporte->user_id]) }}">
                    {{ $transporte->usuario->nombres }} {{ $transporte->usuario->apellidos }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Vehiculo</b>
                <span class="pull-right">{{ $transporte->vehiculo }}</span>
              </li>
              <li class="list-group-item">
                <b>Patente</b>
                <span class="pull-right">{{ $transporte->patente }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9" style="padding:0">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> <i class="fa fa-clipboard"></i> Contratos</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered data-table table-hover" style="width: 100%">
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
                  @foreach($transporte->contratos()->get() as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>{{ $d->contrato->nombre }} </td>
                      <td>{{ $d->created_at }} </td>
                      @if(Auth::user()->tipo <= 2)
                      <td>
                        <button class="btn btn-sm btn-flat btn-danger" data-contrato="{{ $d->id }}" data-toggle="modal" data-target="#delContratoModal"><i class="fa fa-times" aria-hidden="true"></i></button>
                      </td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Consumos</h3>
              <span class="pull-right">
                <a class="btn btn-success btn-flat" href="{{ route('consumos.create', ['transporte' => $transporte->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Consumo</a>
              </span>
            </div>
            <div class="box-body">
              <table id="tableConsumos" class="table table-bordered data-table table-hover" style="width: 100%">
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
                  @foreach($transporte->consumos()->get() as $d)
                    <tr>
                      <td>{{ $loop->index + 1 }}</td>
                      <td>
                        <a href="{{ route('contratos.show', ['contrato' => $d->contrato_id]) }}">
                          {{ $d->contrato->nombre }}
                        </a>
                      </td>
                      <td>{{ $d->tipo() }} </td>
                      <td>{{ $d->fecha() }} </td>
                      <td>{{ $d->valor() }}</td>
                      <td>
                        <a class="btn btn-primary btn-sm btn-flat" href="{{ route('consumos.show', ['consumo' => $d->id]) }}"><i class="fa fa-search"></i></a>
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
  </section>
  @if(Auth::user()->tipo <= 2)
  <div id="addModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="addModalLabel">Agregar a contrato</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('transportes.storeContratos', [$transporte->id]) }}" method="POST">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label" for="contrato">Contrato: *</label>
                <select id="contrato" class="form-control" name="contrato" required>
                  <option value="">Seleccione...</option>
                  @foreach($contratos as $contrato)
                    <option value="{{ $contrato->id }}">{{ $contrato->nombre }}</option>
                  @endforeach
                </select>
              </div>

              <center>
                <button class="btn btn-flat btn-primary" type="submit">Guardar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="delContratoModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delContratoModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delContratoModalLabel">Eliminar Transporte</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="destroyContrato" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Contrato?</h4><br>

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
          <h4 class="modal-title" id="delModalLabel">Eliminar Transporte</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('transportes.destroy', [$transporte->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Transporte?</h4><br>

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
    $('#delContratoModal').on('show.bs.modal', function(e){
      let btn = $(e.relatedTarget),
          contrato = btn.data('contrato');

      $('#destroyContrato').attr('action', `{{route('transportes.show', ['transportes'=>$transporte->id])}}/delete/${contrato}`)
    })
  })
</script>
@endsection