@extends('layouts.app')

@section('title', 'Editar')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Inventario V2</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Inventario V2</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Editar Inventario V2</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.inventario.v2.update', ['inventario' => $inventario->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
                  <label for="nombre">Nombre: *</label>
                  <input id="nombre" class="form-control" type="text" name="nombre" maxlength="50" value="{{ old('nombre', $inventario->nombre) }}" placeholder="Nombre" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('unidad') ? ' has-error' : '' }}">
                  <label for="unidad">Unidad: *</label>
                  <select id="unidad" class="form-control" name="unidad" required>
                    <option value="">Seleccione...</option>
                    @foreach($unidades as $unidad)
                      <option value="{{ $unidad->id }}"{{ old('unidad', $inventario->unidad_id) == $unidad->id ? ' selected' : '' }}>{{ $unidad->nombre }}</option>
                    @endforeach
                  </select>

                  @permission('inventario-unidad-create')
                    <button class="btn btn-simple btn-link btn-sm" type="button" data-toggle="modal" data-target="#optionModal" data-option="unidad"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Unidad</button>
                  @endpermission
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">&nbsp;</label>
                  <div class="custom-control custom-checkbox">
                    <input id="check-codigos" class="custom-control-input" type="checkbox" name="requiere_codigo" value="1"{{ old('requiere_codigo', ($inventario->tipo_codigo || $inventario->codigo)) == '1' ? ' checked' : '' }}>
                    <label class="custom-control-label" for="check-codigos">
                      Requiere código
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4 fields-codigos" style="display: none">
                <div class="form-group">
                  <label for="tipo_codigo">Tipo de código:</label>
                  <input id="tipo_codigo" class="form-control" type="text" name="tipo_codigo" maxlength="6" value="{{ old('tipo_codigo', $inventario->tipo_codigo) }}" placeholder="Tipo de código">
                </div>
              </div>
              <div class="col-md-4 fields-codigos" style="display: none">
                <div class="form-group">
                  <label for="codigo">Código:</label>
                  <input id="codigo" class="form-control" type="text" name="codigo" maxlength="8" value="{{ old('codigo', $inventario->codigo) }}" placeholder="Código">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('bodega') ? ' has-error' : '' }}">
                  <label for="bodega">Bodega:</label>
                  <select id="bodega" class="form-control" name="bodega">
                    <option value="">Seleccione...</option>
                    @foreach($bodegas as $bodega)
                      <option value="{{ $bodega->id }}"{{ old('bodega', $inventario->bodega_id) == $bodega->id ? ' selected' : '' }}>{{ $bodega->nombre }}</option>
                    @endforeach
                  </select>

                  @permission('bodega-create')
                    <button class="btn btn-simple btn-link btn-sm" type="button" data-toggle="modal" data-target="#optionModal" data-option="bodega"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Bodega</button>
                  @endpermission
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('ubicacion') ? ' has-error' : '' }}">
                  <label for="ubicacion">Ubicación:</label>
                  <select id="ubicacion" class="form-control" name="ubicacion" disabled>
                    <option value="">Seleccione...</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('stock_minimo') ? ' has-error' : '' }}">
                  <label for="stock_minimo">Stock mínimo:</label>
                  <input id="stock_minimo" class="form-control" type="number" step="0.01" min="0" max="9999" name="stock_minimo" value="{{ old('stock_minimo', $inventario->stock_minimo) }}" placeholder="Stock mínimo">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group{{ $errors->has('categorias') ? ' has-error' : '' }}">
                  <label for="categorias">Categorías:</label>
                  <select id="categorias" class="form-control" name="categorias[]" multiple="multiple">
                    @foreach($categorias as $categoria)
                      <option value="{{ $categoria->id }}"{{ $inventario->categorias->contains($categoria->id) ? ' selected' : '' }}>{{ $categoria->etiqueta }}</option>
                    @endforeach
                  </select>

                  @permission('etiqueta-create')
                    <button class="btn btn-simple btn-link btn-sm" type="button" data-toggle="modal" data-target="#optionModal" data-option="categorias"><i class="fa fa-plus" aria-hidden="true"></i> Agregar Categoría</button>
                  @endpermission
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-8">
                <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                  <label for="descripcion">Descripción:</label>
                  <textarea id="descripcion" class="form-control" name="descripcion" cols="30" rows="3" maxlength="250">{{ old('descripcion', $inventario->descripcion) }}</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="text-center">
                    <a id="foto-link" href="#" type="button">
                      <img id="foto-placeholder" class="img-fluid border" src="{{ $inventario->foto ? $inventario->foto_url : asset('images/default.jpg') }}" alt="foto" style="max-height:120px;margin: 0 auto;">
                    </a>
                  </div>
                  <label for="foto">Foto:</label>
                  <div class="custom-file">
                    <input id="foto" class="custom-file-input" type="file" name="foto" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png">
                    <label class="custom-file-label" for="foto">Seleccionar</label>
                  </div>
                  <small class="form-text text-muted">Tamaño máximo permitido: 3MB</small>
                </div>
              </div>
            </div>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.v2.show', ['inventario' => $inventario->id] ) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @permission('inventario-unidad-create|etiqueta-create')
    <div id="optionModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="optionModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="option-form" action="#" method="POST">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="optionModalLabel">Agregar Unidad</h4>
            </div>
            <div class="modal-body">
              
              <div class="form-group">
                <label class="control-label" for="option-nombre">Nombre: *</label>
                <input id="option-nombre" class="form-control" type="text" name="nombre" maxlength="50" required>
              </div>

              <div class="alert alert-dismissible alert-danger alert-option" role="alert" style="display: none">
                <strong class="text-center">Ha ocurrido un error</strong> 

                <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
              <button class="btn btn-primary btn-sm option-submit" type="submit">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endpermission
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    let defaultImge = @json(asset('images/default.jpg'));

    @permission('inventario-unidad-create|etiqueta-create|bodega-create')
      const alertOption = $('.alert-option');
      const optionSubmit = $('.option-submit');
    @endpermission

    $(document).ready(function(){
      $('#unidad, #bodega, #ubicacion, #categorias').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#bodega').change(searchUbicaciones)
      $('#bodega').change();

      $('#check-codigos').change(function () {
        let isChecked = $(this).is(':checked');
        $('.fields-codigos').toggle(isChecked);
        if(!isChecked){
          $('.fields-codigos input').val(null); 
        }
      });
      $('#check-codigos').change();

      $('#foto-link').click(function (e) {
        e.preventDefault();

        $('#foto').trigger('click');
      });

      $('#foto').change(function () {
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

      @permission('inventario-unidad-create|etiqueta-create|bodega-create')
        $('#optionModal').on('show.bs.modal', function (e){
          let option = $(e.relatedTarget).data('option');

          let url = option == 'unidad' ? '{{ route("admin.unidad.store") }}' : (option == 'categorias' ? '{{ route("admin.etiqueta.store") }}' : '{{ route("admin.bodega.store") }}');
          let title = option == 'unidad' ? 'Unidad' : (option == 'categorias' ? 'Categoría' : 'Bodega');

          $('#optionModalLabel').text(`Agregar ${title}`);
          $('#option-form').attr('action', url);
          optionSubmit.data('option', option);
        });

        $('#option-form').submit(function(e){
          e.preventDefault();

          optionSubmit.prop('disabled', true);

          let form = $(this),
              action = form.attr('action'),
              option = optionSubmit.data('option'),
              field = (option == 'unidad' || option == 'bodega') ? 'nombre' : 'etiqueta';
          let data = {
            _token: '{{ csrf_token() }}',
            [field]: $('#option-nombre').val()
          };

          $.ajax({
            type: 'POST',
            data: data,
            url: action,
            dataType: 'json'
          })
          .done(function (response) {
            if(response.response){
              let indexName = option != 'categorias' ? option : 'etiqueta';
              let value = response[indexName].id;
              let nombre = option != 'categorias' ? response[indexName].nombre : response.etiqueta.etiqueta;

              $(`#${option}`).append(`<option value="${value}">${nombre}</option`);

              if(option == 'categorias'){
                let ids = $('#categorias').val();
                ids.push(value);
                value = ids;
              } 

              $(`#${option}`).val(value);
              $(`#${option}`).trigger('change');
              $('#option-form')[0].reset();
              $('#optionModal').modal('hide');
            }else{
              alertOption.show().delay(7000).hide('slow');  
            }
          })
          .fail(function () {
            alertOption.show().delay(7000).hide('slow');
          })
          .always(function () {
            optionSubmit.prop('disabled', false);
          })
        });
      @endpermission
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(name){
      $('#foto').siblings(`label[for="foto"]`).text(name);
    }

    function preview(input) {
      let reader = new FileReader();
  
      reader.onload = function (e){
        let holder = document.getElementById('foto-placeholder');
        holder.src = e.target.result;
      }

      reader.readAsDataURL(input);
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`);
      $('.alert').show().delay(5000).hide('slow');
      $('#foto').val('');
    }

    function searchUbicaciones() {
      let bodega = $(this).val();

      if(!bodega){
        return false;
      }

      let url = '{{ route("admin.bodega.ubicaciones", ["bodega" => ":id"]) }}'.replace(':id', bodega);

      $('#ubicacion').empty().prop('disabled', true);

      $.ajax({
        type: 'GET',
        url: url,
        data: {},
        dataType: 'json'
      })
      .done(function (response) {
        $('#ubicacion').empty();

        $.each(response, function (k, ubicacion) {
          let oldSelected = @json(old('ubicacion', $inventario->ubicacion_id)) == ubicacion.id;
          $('#ubicacion').append(`<option value="${ubicacion.id}"${oldSelected ? ' selected' : ''}>${ubicacion.nombre}</option>`);
        });
      })
      .fail(function () {
        $('#ubicacion').empty().prop('disabled', true);
      })
      .always(function () {
        $('#ubicacion').prop('disabled', false);
      });
    }
  </script>
@endsection
