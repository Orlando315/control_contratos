@extends('layouts.app')
@section('title','Editar -'.config('app.name'))
@section('header','Editar')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('inventarios.index') }}">Inventarios</a></li>
    <li class="active">Editar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form class="" action="{{ route('inventarios.update', ['id' => $inventario->id]) }}" method="POST" enctype="multipart/form-data">

        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar inventario</h4>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('tipo') ? ' has-error' : '' }}">
              <label class="control-label" class="form-control" for="tipo">Tipo: *</label>
              <select id="tipo" class="form-control" name="tipo" required>
                <option value="">Seleccione...</option>
                @if(Auth::user()->tipo < 3)
                  <option value="1"{{ old('tipo', $inventario->tipo) == '1' ? ' selected' : '' }}>Insumo</option>
                  <option value="2"{{ old('tipo', $inventario->tipo) == '2' ? ' selected' : '' }}>EPP</option>
                  <option value="4"{{ old('tipo', $inventario->tipo) == '4' ? ' selected' : '' }}>Equipo</option>
                  <option value="5"{{ old('tipo', $inventario->tipo) == '5' ? ' selected' : '' }}>Maquinaria</option>
                @endif
                <option value="3"{{ old('tipo', $inventario->tipo) == '3' ? ' selected' : '' }}>Otro</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label class="control-label" for="nombre">Nombre: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $inventario->nombre) }}" placeholder="Nombre" required>
            </div>            
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('valor') ? ' has-error' : '' }}">
              <label class="control-label" for="valor">Valor: *</label>
              <input id="valor" class="form-control" type="number" step="1" min="1" max="9999999999999" name="valor" value="{{ old('valor', $inventario->valor) }}" placeholder="Valor" required>
            </div>            
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
              <label class="control-label" for="fecha">Fecha: *</label>
              <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha', $inventario->fecha) }}" placeholder="dd-mm-yyyy" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('cantidad') ? ' has-error' : '' }}">
              <label class="control-label" for="cantidad">Cantidad: *</label>
              <input id="cantidad" class="form-control" type="number" step="1" min="1" max="999999" name="cantidad" value="{{ old('cantidad', $inventario->cantidad) }}" placeholder="Valor" required>
            </div>            
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('stock_critico') ? ' has-error' : '' }}">
              <label class="control-label" for="stock_critico">Stock crítico:</label>
              <input id="stock_critico" class="form-control" type="number" step="1" min="1" max="999" name="stock_critico" value="{{ old('stock_critico', $inventario->low_stock) }}" placeholder="Stcok crítico">
            </div>
          </div>
        </div>

        <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
          <label class="control-label" for="descripcion">Descripción:</label>
          <input id="descripcion" class="form-control" type="text" name="descripcion" maxlength="200" value="{{ old('descripcion', $inventario->descripcion) }}" placeholder="Descripción">
        </div>

        <div class="form-group{{ $errors->has('observacion') ? ' has-error' : '' }}">
          <label class="control-label" for="observacion">Observación:</label>
          <input id="observacion" class="form-control" type="text" name="observacion" maxlength="200" value="{{ old('observacion', $inventario->observacion) }}" placeholder="Observación">
        </div>

        <div class="form-group{{ $errors->has('adjunto') ? ' has-error' : '' }}">
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
          <a class="btn btn-flat btn-default" href="{{ route('inventarios.show', [$inventario->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#tipo').select2({
      placeholder: 'Seleccione...'
    })

    $('#fecha').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });
  });
</script>
@endsection
