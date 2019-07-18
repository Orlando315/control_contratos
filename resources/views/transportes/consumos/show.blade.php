@extends( 'layouts.app' )

@section( 'title', 'Consumo - '.config( 'app.name' ) )
@section( 'header', 'Consumo' )
@section( 'breadcrumb' )
	<ol class="breadcrumb">
	  <li><a href="{{ route( 'dashboard' ) }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('transportes.show', ['transporte' => $consumo->transporte_id]) }}">Transportes</a></li>
	  <li class="active"> Consumo </li>
	</ol>
@endsection
@section( 'content' )
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('transportes.show', ['transporte' => $consumo->transporte_id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    <a class="btn btn-flat btn-success" href="{{ route('consumos.edit', [$consumo->id]) }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <h4 class="profile-username text-center">
              Datos del consumo
            </h4>
            <p class="text-muted text-center">{{ $consumo->created_at }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  <a href="{{ route('contratos.show', ['contrato' => $consumo->contrato_id]) }}">
                    {{ $consumo->contrato->nombre }}
                  </a>
                </span>
              </li>
              <li class="list-group-item">
                <b>Fecha</b>
                <span class="pull-right">{{ $consumo->fecha() }}</span>
              </li>
              <li class="list-group-item">
                <b>Tipo</b>
                <span class="pull-right">{{ $consumo->tipo() }}</span>
              </li>
              @if($consumo->tipo == 2)
                <li class="list-group-item">
                  <b>Cantidad</b>
                  <span class="pull-right">{{ $consumo->cantidad() }}</span>
                </li>
              @endif
              <li class="list-group-item">
                <b>Valor</b>
                <span class="pull-right">{{ $consumo->valor }}</span>
              </li>
              <li class="list-group-item">
                <b>Chofer</b>
                <span class="pull-right">{{ $consumo->chofer }}</span>
              </li>
              <li class="list-group-item">
                <b>Observación</b>
                <span class="pull-right">{{ $consumo->observacion ?? 'N/A' }}</span>
              </li>
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-9">
        <div class="col-md-12" style="margin-bottom: 5px">
          <h4>
            Adjuntos
            @if($consumo->adjuntos()->count() < 10)
            <span class="pull-right">
              <a class="btn btn-flat btn-success btn-sm" href="{{ route('consumos.adjuntos.create', ['consumo' => $consumo->id]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Agregar</a>
            </span>
            @endif
          </h4>
        </div>
        @foreach($consumo->adjuntos()->get() as $adjunto)
          <div id='adjunto-{{$adjunto->id}}' class='col-md-4 col-sm-6 col-xs-12'>
            {!! $adjunto->generateThumb() !!}
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delModalLabel">Eliminar Consumo</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('consumos.destroy', [$consumo->id]) }}" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Consumo?</h4><br>

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

  <div id="delFileModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delFileModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="delFileModalLabel">Eliminar adjunto</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form id="delete-file-form" class="col-md-8 col-md-offset-2" action="#" method="POST">
              {{ method_field('DELETE') }}
              {{ csrf_field() }}
              <h4 class="text-center">¿Esta seguro de eliminar este Adjunto?</h4><br>

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
    $(document).ready(function (){
      $('#delFileModal').on('show.bs.modal', function(e){
        let button = $(e.relatedTarget),
            action = button.data('url');

        $('#delete-file-form').attr('action', action);
      });

      $('#delete-file-form').submit(deleteFile);
    })

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
