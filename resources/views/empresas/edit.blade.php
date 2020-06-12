@extends('layouts.app')
@section('title', 'Perfil - '.config('app.name'))
@section('header', 'Perfil')
@section('breadcrumb')
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('usuarios.perfil') }}" title="Perfil"> Perfil </a></li>
    <li class="active">Editar</li>
  </ol>
@endsection

@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <form action="{{ route('empresas.update') }}" method="POST" enctype="multipart/form-data">
        {{ method_field('PATCH') }}
        {{ csrf_field() }}

        <h4>Editar Perfil</h4>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
              <label class="control-label" for="nombres">Nombre: *</label>
              <input id="nombres" class="form-control" type="text" name="nombres" value="{{ old( 'nombres' ) ? old( 'nombres' ) : Auth::user()->nombres }}" placeholder="Nombre" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
              <label class="control-label" for="rut">RUT: *</label>
              <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dkK])$" value="{{ old( 'rut' ) ? old( 'rut' ) : Auth::user()->rut }}" placeholder="RUT" required>
              <span class="help-block">Ejemplo: 00000000-0</span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('representante') ? ' has-error' : '' }}">
              <label class="control-label" for="representante">Representante: *</label>
              <input id="representante" class="form-control" type="text" name="representante" value="{{ old('representante') ? old('representante') : Auth::user()->empresa->representante }}" placeholder="Representante" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label class="control-label" for="email">Email: *</label>
              <input id="email" class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : Auth::user()->email }}" placeholder="Email" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
              <label class="control-label" for="telefono">Teléfono: *</label>
              <input id="telefono" class="form-control" type="text" name="telefono" value="{{ old('telefono') ? old('telefono') : Auth::user()->telefono }}" placeholder="Teléfono" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('jornada') ? ' has-error' : '' }}">
              <label class="control-label" class="form-control" for="jornada">Jornada: *</label>
              <select id="jornada" class="form-control" name="jornada" required>
                <option value="">Seleccione...</option>
                <option value="5x2"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '5x2' ? ' selected' : '' }}>5x2</option>
                <option value="4x3"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '4x3' ? ' selected' : '' }}>4x3</option>
                <option value="6x1"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '6x1' ? ' selected' : '' }}>6x1</option>
                <option value="7x7"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '7x7' ? ' selected' : '' }}>7x7</option>
                <option value="10x10"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '10x10' ? ' selected' : '' }}>10x10</option>
                <option value="12x12"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '12x12' ? ' selected' : '' }}>12x12</option>
                <option value="20x10"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '20x10' ? ' selected' : '' }}>20x10</option>
                <option value="7x14"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '7x14' ? ' selected' : '' }}>7x14</option>
                <option value="14x14"{{ old('jornada', Auth::user()->empresa->configuracion->jornada) == '14x14' ? ' selected' : '' }}>14x14</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group{{ $errors->has('dias_vencimiento') ? ' has-error' : '' }}">
              <label class="control-label" for="dias_vencimiento">Días antes del vencimiento: *</label>
              <input id="dias_vencimiento" class="form-control" type="number" name="dias_vencimiento" min="1" max="255" value="{{ old('dias_vencimiento', Auth::user()->empresa->configuracion->dias_vencimiento) }}" placeholder="Días vencimiento" required>
              <span class="help-block">Cantidad de días restantes al vencimiento de un Contrato / Documento</span>
            </div>
          </div>
        </div>

        <div id="foto-group" class="form-group">
          <section>
            <a id="logo-link" href="#" type="button">
              <img id="logo-placeholder" class="img-responsive" src="{{ Auth::user()->empresa->logo_url }}" alt="logo" style="max-height:120px;margin: 0 auto;max-width:250px;">
            </a>
          </section>
          <label for="logo">Logo:</label>
          <div class="file-loading">
            <input id="logo" type="file" name="logo" data-msg-placeholder="Seleccionar logo" multiple accept="image/jpeg,image/png">
          </div>
          <small class="help-block">Tamaño máximo permitido: 3MB</small>
        </div>

        <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ route('usuarios.perfil') }}"><i class="fa fa-reply"></i> Atras</a>
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script type="text/javascript">
    let defaultImge = @json(asset('images/default.jpg'));

    $(document).ready(function () {
      $('#logo-link').click(function (e) {
        e.preventDefault();

        $('#logo').trigger('click')
      })

      $('#logo').change(function () {
        if(this.files && this.files[0]){
          let file = this.files[0];

          if(['image/png', 'image/jpeg'].includes(file.type)){
            if(file.size < 3000000){
              preview(this.files[0])
            }else{
              showAlert('La imagen debe ser menor a 3MB.')
              return false;
            }
          }else{
            showAlert('El archivo no es un tipo de imagen valida.')
            return false;
          }
        }
      })
    })

    function preview(input) {
      let reader = new FileReader();
  
      reader.onload = function (e){
        let holder = document.getElementById('logo-placeholder')
        holder.src = e.target.result
      }

      reader.readAsDataURL(input)
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty()
      $('.alert ul').append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
      $('#logo').val('')
      document.getElementById('logo-placeholder').src = defaultImge
    }
  </script>
@endsection
