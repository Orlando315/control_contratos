@extends( 'layouts.app' )
@section( 'title', 'Sueldos - '.config( 'app.name' ) )
@section( 'header','Sueldos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}">Sueldos</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('sueldos.store', ['contrato' => $contrato->id]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <h4>Realizar pagos</h4>
        
        <p><b>Contrato:</b> {{ $contrato->nombre }}</p>
        <p><b>Empleados:</b> {{ $contrato->empleados()->count() }}</p>
        <p><b>Mes a pagar:</b>
          <span class="text-danger">
            @if($paymentMonth)
              {{ $paymentMonth }}
            @else
              Ya existen pagos registrados este mes.
            @endif
          </span>
        </p>
        <hr>
        <div class="form-group {{ $errors->has('adjunto') ? 'has-error' : '' }}">
          <label class="control-label" for="adjunto">Adjunto: </label>
          <input id="adjunto" type="file" name="adjunto" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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
          <a class="btn btn-flat btn-default" href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply"></i> Atras</a>
          @if($paymentMonth)
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
          @endif
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
