@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.manage.empresa.index') }}">Empresas</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar empresa</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.manage.empresa.update', ['empresa' => $empresa->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
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

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('jornada') ? ' has-error' : '' }}">
                    <label for="jornada">Jornada: *</label>
                    <select id="jornada" class="custom-select" name="jornada" required>
                      <option value="">Seleccione...</option>
                      <option value="5x2"{{ old('jornada', $empresa->configuracion->jornada) == '5x2' ? ' selected' : '' }}>5x2</option>
                      <option value="4x3"{{ old('jornada', $empresa->configuracion->jornada) == '4x3' ? ' selected' : '' }}>4x3</option>
                      <option value="6x1"{{ old('jornada', $empresa->configuracion->jornada) == '6x1' ? ' selected' : '' }}>6x1</option>
                      <option value="7x7"{{ old('jornada', $empresa->configuracion->jornada) == '7x7' ? ' selected' : '' }}>7x7</option>
                      <option value="10x10"{{ old('jornada', $empresa->configuracion->jornada) == '10x10' ? ' selected' : '' }}>10x10</option>
                      <option value="12x12"{{ old('jornada', $empresa->configuracion->jornada) == '12x12' ? ' selected' : '' }}>12x12</option>
                      <option value="20x10"{{ old('jornada', $empresa->configuracion->jornada) == '20x10' ? ' selected' : '' }}>20x10</option>
                      <option value="7x14"{{ old('jornada', $empresa->configuracion->jornada) == '7x14' ? ' selected' : '' }}>7x14</option>
                      <option value="14x14"{{ old('jornada', $empresa->configuracion->jornada) == '14x14' ? ' selected' : '' }}>14x14</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('dias_vencimiento') ? ' has-error' : '' }}">
                    <label for="dias_vencimiento">Días antes del vencimiento:</label>
                    <input id="dias_vencimiento" class="form-control" type="number" name="dias_vencimiento" min="1" max="255" value="{{ old('dias_vencimiento', $empresa->configuracion->dias_vencimiento) }}" placeholder="Días vencimiento">
                    <span class="form-text text-muted">Cantidad de días restantes al vencimiento de un Contrato / Documento</span>
                  </div>
                </div>
              </div>
            </section>

            <section>
              <legend class="form-legend">Contacto</legend>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('representante_legal') ? ' has-error' : '' }}">
                    <label for="representante_legal">Representante legal: *</label>
                    <input id="representante_legal" class="form-control" type="text" name="representante_legal" maxlength="50" value="{{ old('representante_nombre', $empresa->representante) }}" placeholder="Representante legal" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.manage.empresa.show', ['empresa' => $empresa->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
    let OLD_RUT = '';

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
              changeLabel(file.name)
              preview(this.files[0])
            }else{
              changeLabel('Seleccionar')
              showAlert('La imagen debe ser menor a 3MB.')
              return false;
            }
          }else{
            changeLabel('Seleccionar')
            showAlert('El archivo no es un tipo de imagen valida.')
            return false;
          }
        }
      })

      $('#rut_empresa').change(function () {
        let isChecked = $(this).is(':checked');
        let rutEmpresa = $('#rut').val();
        OLD_RUT = isChecked ? $('#usuario_rut').val() : OLD_RUT;

        $('#usuario_rut').prop({'disabled': isChecked, 'required': !isChecked}).val(isChecked ? rutEmpresa : OLD_RUT);

      })
      $('#rut_empresa').change();
    })

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
