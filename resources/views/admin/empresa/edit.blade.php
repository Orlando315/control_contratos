@extends('layouts.app')

@section('title', 'Empresa')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresa</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.empresa.perfil') }}">Empresas</a></li>
        <li class="breadcrumb-item active">Editar</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-building"></i> Editar</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.empresa.update') }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <section>
              <legend class="form-legend">Información de la empresa</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                    <label for="rut">RUT: *</label>
                    <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" value="{{ old('rut', $empresa->rut) }}" placeholder="RUT" required>
                    <small class="form-text text-muted">Ejemplo: 00000000-0</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('razon_social') ? ' has-error' : '' }}">
                    <label for="razon_social">Razón social: *</label>
                    <input id="razon_social" class="form-control" type="text" name="razon_social" maxlength="50" value="{{ old('razon_social', $empresa->nombre) }}" placeholder="Razón social" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <div class="text-center">
                      <a id="logo-link" href="#" type="button">
                        <img id="logo-placeholder" class="img-fluid border" src="{{ $empresa->logo_url }}" alt="logo" style="max-height:120px;margin: 0 auto;">
                      </a>
                    </div>
                    <label for="logo">Logo:</label>
                    <div class="custom-file">
                      <input id="logo" class="custom-file-input" type="file" name="logo" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png">
                      <label class="custom-file-label" for="logo">Seleccionar</label>
                    </div>
                    <small class="form-text text-muted">Tamaño máximo permitido: 3MB</small>
                  </div>
                </div>
              </div>
            </section>

            <section>
              <legend class="form-legend">Representante legal</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('representante_nombre') ? ' has-error' : '' }}">
                    <label for="representante_nombre">Nombre: *</label>
                    <input id="representante_nombre" class="form-control" type="text" name="representante_nombre" maxlength="50" value="{{ old('representante_nombre', $empresa->representante) }}" placeholder="Nombre" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                    <label for="telefono">Teléfono:</label>
                    <input id="telefono" class="form-control" type="text" name="telefono" maxlength="20" value="{{ old('telefono', $empresa->telefono) }}" placeholder="Teléfono">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email:</label>
                    <input id="email" class="form-control" type="email" name="email" maxlength="50" value="{{ old('email', $empresa->email) }}" placeholder="Email">
                  </div>
                </div>
              </div>
            </section>

            <div class="alert alert-danger alert-important"{!! $errors->any() ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.empresa.perfil') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    let defaultImge = @json(asset('images/default.jpg'));

    $(document).ready(function () {
      $('#logo-link').click(function (e) {
        e.preventDefault();

        $('#logo').trigger('click');
      });

      $('#logo').change(function () {
        if(this.files && this.files[0]){
          let file = this.files[0];

          if(['image/png', 'image/jpeg'].includes(file.type)){
            if(file.size < 3000000){
              changeLabel(file.name);
              preview(this.files[0]);
            }else{
              changeLabel('Seleccionar');
              showAlert('La imagen debe ser menor a 3MB.');
              return false;
            }
          }else{
            changeLabel('Seleccionar');
            showAlert('El archivo no es un tipo de imagen valida.');
            return false;
          }
        }
      });
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(name){
      $('#logo').siblings(`label[for="logo"]`).text(name);
    }

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
