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
                <b>Contrato</b>
                <span class="pull-right">
                  <a href="{{ route('contratos.show', ['contrato' => $transporte->contrato_id]) }}">
                    {{ $transporte->contrato->nombre }}
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

      <div class="col-md-9">
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
  </section>
  @if(Auth::user()->tipo <= 2)
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