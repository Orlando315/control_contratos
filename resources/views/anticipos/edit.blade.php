@extends( 'layouts.app' )
@section( 'title', 'Anticipos - '.config( 'app.name' ) )
@section( 'header','Anticipos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('anticipos.index') }}">Anticipos</a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('anticipos.update', ['anticipo' => $anticipo->id]) }}" method="POST">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar anticipo</h4>

        <div class="form-group {{ $errors->has('fecha') ? 'has-error' : '' }}">
          <label class="control-label" for="fecha">Fecha: *</label>
          <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') ? old('fecha') : $anticipo->fecha }}" placeholder="dd-mm-yyyy" required>
        </div>

        <div class="form-group {{ $errors->has('anticipo') ? 'has-error' : '' }}">
          <label class="control-label" for="anticipo">Anticipo: *</label>
          <input id="anticipo" class="form-control" type="number" step="1" min="1" maxlength="999999" name="anticipo" value="{{ old('anticipo') ? old('anticipo') : $anticipo->anticipo }}" placeholder="Anticipo" required>
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
    $('#fecha').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });
  });
</script>
@endsection
