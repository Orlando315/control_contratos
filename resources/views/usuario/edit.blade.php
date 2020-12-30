@extends('layouts.app')

@section('title', 'Perfil')

@section('head')
  @if(Auth::user()->isAdmin())
    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
  @endif
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar Perfil</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                  <label for="nombres">Nombre: *</label>
                  <input id="nombres" class="form-control" type="text" name="nombres" value="{{ old('nombres', Auth::user()->nombres) }}" placeholder="Nombres" required>
                </div>
              </div>
              <div class="col-md-6">
                @if(Auth::user()->isAdmin())
                  <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                    <label for="rut">RUT: *</label>
                    <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dkK])$" value="{{ old( 'rut' ) ? old( 'rut' ) : Auth::user()->rut }}" placeholder="RUT" required>
                    <span class="form-text text-muted">Ejemplo: 00000000-0</span>
                  </div>
                @else
                  <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                    <label for="apellidos">Apellido: *</label>
                    <input id="apellidos" class="form-control" type="text" name="apellidos" value="{{ old('apellidos', Auth::user()->apellidos) }}" placeholder="Apellidos" required>
                  </div>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono:</label>
                  <input id="telefono" class="form-control" type="text" name="telefono" maxlength="20" value="{{ old('telefono', Auth::user()->telefono) }}" placeholder="Teléfono">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email: *</label>
                  <input id="email" class="form-control" type="email" name="email" value="{{ old('email') ? old('email') : Auth::user()->email }}" placeholder="Email" required>
                </div>
              </div>
            </div>
            
            @if(Auth::user()->isAdmin())
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('representante') ? ' has-error' : '' }}">
                    <label for="representante">Representante: *</label>
                    <input id="representante" class="form-control" type="text" name="representante" value="{{ old('representante') ? old('representante') : Auth::user()->empresa->representante }}" placeholder="Representante" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group{{ $errors->has('jornada') ? ' has-error' : '' }}">
                    <label for="jornada">Jornada: *</label>
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
                    <label for="dias_vencimiento">Días antes del vencimiento: *</label>
                    <input id="dias_vencimiento" class="form-control" type="number" name="dias_vencimiento" min="1" max="255" value="{{ old('dias_vencimiento', Auth::user()->empresa->configuracion->dias_vencimiento) }}" placeholder="Días vencimiento" required>
                    <span class="form-text text-muted">Cantidad de días restantes al vencimiento de un Contrato / Documento</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <section class="text-center">
                  <a id="logo-link" href="#" type="button">
                    <img id="logo-placeholder" class="img-responsive" src="{{ Auth::user()->empresa->logo_url }}" alt="logo" style="max-height:120px;margin: 0 auto;max-width:250px;">
                  </a>
                </section>
                <label for="logo">Logo:</label>
                <div class="custom-file">
                  <input id="logo" class="custom-file-input" type="file" name="logo" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png">
                  <label class="custom-file-label" for="logo">Seleccionar</label>
                </div>
                <small class="form-text text-muted">Tamaño máximo permitido: 3MB</small>
              </div>
            @endif


            @if(Auth::user()->isEmpresa())
              <section>
                <legend class="form-legend">Facturación Sii</legend>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('sii_clave') ? ' has-error' : '' }}">
                      <label for="sii_clave">Clave Sii:</label>
                      <input id="sii_clave" class="form-control" type="text" name="sii_clave" maxlength="120" value="{{ old('sii_clave', Auth::user()->empresa->configuracion->sii_clave) }}" placeholder="Clave SII">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('sii_clave_certificado') ? ' has-error' : '' }}">
                      <label for="sii_clave_certificado">Clave certificado digital:</label>
                      <input id="sii_clave_certificado" class="form-control" type="text" name="sii_clave_certificado" maxlength="150" value="{{ old('sii_clave_certificado', Auth::user()->empresa->configuracion->sii_clave_certificado) }}" placeholder="Clave certificado digital">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group{{ $errors->has('firma') ? ' has-error' : '' }}">
                      <label for="firma">Firma:</label>
                      <input id="firma" class="form-control" type="text" name="firma" maxlength="120" value="{{ old('firma', Auth::user()->empresa->configuracion->firma) }}" placeholder="Firma">
                    </div>
                  </div>
                </div>
              </section>
            @endif

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('perfil') }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('script')
  @if(Auth::user()->isAdmin())
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
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

        $('#jornada').select2({
          theme: 'bootstrap4',
          placeholder: 'Seleccionar...',
        })
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
  @endif
@endsection
