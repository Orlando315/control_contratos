@extends( 'layouts.app' )

@section( 'title', 'Inventario - '.config( 'app.name' ) )
@section( 'header', 'Inventario' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('inventarios.index') }}">Inventarios</a></li>
	  <li class="active"> Inventario </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('inventarios.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('inventarios.edit', [$inventario->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del inventario
            </h4>
            <p class="text-muted text-center"></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Tipo</b>
                <span class="pull-right">{{ $inventario->tipo() }}</span>
              </li>
              <li class="list-group-item">
                <b>Nombre</b>
                <span class="pull-right">{{ $inventario->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Valor</b>
                <span class="pull-right">{{ $inventario->valor() }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right"> {{ $inventario->fecha }} </span>
              </li>
              <li class="list-group-item">
                <b>Cantidad</b>
                <span class="pull-right"> {{ $inventario->cantidad() }} </span>
              </li>
              <li class="list-group-item">
                <b>Adjunto</b>
                <span class="pull-right">{!! $inventario->adjunto() !!}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title">Entregas</h3>
            <span class="pull-right">
              <a class="btn btn-success btn-flat" href="{{ route('entregas.create', ['inventario' => $inventario->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Nueva Entrega</a>
            </span>
          </div>
          <div class="box-body">
            <table class="table data-table table-bordered table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Realizado por</th>
                  <th class="text-center">Entregado a</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Recibido</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-center">
                @foreach($inventario->entregas()->get() as $d)
                  <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $d->realizadoPor->nombres }} {{ $d->realizadoPor->apellidos }}</td>
                    <td>{{ $d->entregadoA->nombres }} {{ $d->entregadoA->apellidos }}</td>
                    <td>{{ $d->cantidad() }}</td>
                    <td>{{ $d->created_at }}</td>
                    <td>{!! $d->recibido() !!}</td>
                    <td>
                      @if(!$d->recibido)
                      <button class="btn btn-flat btn-danger btn-sm" data-toggle="modal" data-target="#delEntregaModal" data-entrega="{{ $d->id }}"><i class="fa fa-times" aria-hidden="true"></i></button>
                      @endif
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

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Inventario</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('inventarios.destroy', [$inventario->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Inventario?</h4><br>

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

  <div id="delEntregaModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delEntregaModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delEntregaModalLabel">Eliminar Entrega</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delete-entrega" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar esta Entrega?</h4><br>

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
      $('#delEntregaModal').on('show.bs.modal', function(e){
        var button  = $(e.relatedTarget),
            entrega = button.data('entrega'),
            action  = '{{ route("entregas.index") }}/{{ $inventario->id }}/' + entrega;

        $('#delete-entrega').attr('action', action);
      });
    });
  </script>
@endsection
