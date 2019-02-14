@extends( 'layouts.app' )
@section( 'title', 'Entregas - '.config( 'app.name' ) )
@section( 'header','Entregas' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('inventarios.index') }}">Inventarios</a></li>
    <li>Entregas</li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('entregas.store', ['inventario' => $inventario]) }}" method="POST">
        {{ csrf_field() }}

        <h4>Agregar entrega - {{ $inventario->nombre }}</h4>

        <div class="form-group {{ $errors->has('empleado_id') ? 'has-error' : '' }}">
          <label class="control-label" for="empleado_id">Empleado: *</label>
          <select id="empleado_id" class="form-control" name="empleado_id" required>
            <option value="">Seleccione...</option>
            @foreach($empleados as $empleado)
              <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected':'' }}>{{ $empleado->usuario->nombres }} {{ $empleado->usuario->apellidos }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group {{ $errors->has('cantidad') ? 'has-error' : '' }}">
          <label class="control-label" for="cantidad">Cantidad: *</label>
          <input id="cantidad" class="form-control" type="number" step="1" min="1" maxlength="999999" name="cantidad" value="{{ old('cantidad') ? old('cantidad') : '' }}" placeholder="Cantidad" required>
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
