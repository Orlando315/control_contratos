@extends( 'layouts.app' )
@section( 'title', 'Documentos - '.config( 'app.name' ) )
@section( 'header','Documentos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('empleados.show', ['empelado' => $documento->empleado_id]) }}">Empleado</a></li>
    <li>Documentos</li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('documentos.update', ['documento' => $documento->id]) }}" method="POST" enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar documento</h4>

        <div class="form-group {{ $errors->has('nombre') ? 'has-error' : '' }}">
          <label class="control-label" for="nombre">Nombre: *</label>
          <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre') ? old('nombre') : $documento->nombre }}" placeholder="Nombre" required>
        </div>

        <div class="form-group {{ $errors->has('vencimiento') ? 'has-error' : '' }}">
          <label class="control-label" for="vencimiento">Vencimiento:</label>
          <input id="vencimiento" class="form-control" type="text" name="vencimiento" value="{{ old( 'vencimiento' ) ? old( 'vencimiento' ) : $documento->vencimiento }}" placeholder="Vencimiento">
        </div>

        @if (count($errors) > 0)
        <div class="alert alert-danger alert-important">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>  
        </div>
        @endif

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ url()->previous() }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#vencimiento').datepicker({
      format: 'dd-mm-yyyy',
      startDate: 'today',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });
  });
</script>
@endsection