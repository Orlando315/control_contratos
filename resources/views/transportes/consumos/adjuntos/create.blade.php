@extends( 'layouts.app' )
@section( 'title', 'Adjuntos - '.config( 'app.name' ) )
@section( 'header','Adjuntos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('consumos.show', ['consumo' => $consumo->id]) }}">Consumo</a></li>
    <li>Adjuntos</li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('consumos.adjuntos.store', ['consumo' => $consumo->id]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <h4>Agregar adjunto</h4>

        <div class="form-group {{ $errors->has('adjunto') ? 'has-error' : '' }}">
          <label class="control-label" for="adjunto">Adjunto: *</label>
          <input id="adjunto" type="file" name="adjunto" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required>
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
