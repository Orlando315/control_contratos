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
      <h2>Ingreso de Stock</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Inventarios V2</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.show', ['inventario' =>  $ingreso->inventario_id]) }}">Ingreso de Stock</a></li>
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
          <h4>Editar Ingreso de Stock</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.inventario.ingreso.update', ['ingreso' => $ingreso->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('proveedor') ? ' has-error' : '' }}">
                  <label for="proveedor">Proveedor:</label>
                  <select id="proveedor" class="form-control" name="proveedor">
                    <option value="">Seleccione...</option>
                    @foreach($proveedores as $proveedor)
                      <option value="{{ $proveedor->id }}"{{ old('proveedor', $ingreso->proveedor_id) == $proveedor->id ? ' selected' : '' }}>
                        {{ $proveedor->rut }} | {{ $proveedor->nombre }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('cantidad') ? ' has-error' : '' }}">
                  <label for="cantidad">Cantidad: *</label>
                  <input id="cantidad" class="form-control" type="number" name="cantidad" min="0" max="9999" value="{{ old('cantidad', $ingreso->cantidad) }}" placeholder="Cantidad" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('costo') ? ' has-error' : '' }}">
                  <label for="costo">Costo:</label>
                  <input id="costo" class="form-control" type="number" name="costo" min="0" max="999999999" step="0.01" value="{{ old('costo', $ingreso->costo) }}" placeholder="Costo">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-8">
                <div class="form-group{{ $errors->has('descripcion') ? ' has-error' : '' }}">
                  <label for="descripcion">Descripción:</label>
                  <textarea id="descripcion" class="form-control" name="descripcion" cols="30" rows="3" maxlength="250">{{ old('descripcion', $ingreso->descripcion) }}</textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="text-center">
                    <a id="foto-link" href="#" type="button">
                      <img id="foto-placeholder" class="img-fluid border" src="{{ $ingreso->foto ? $ingreso->foto_url : asset('images/default.jpg') }}" alt="foto" style="max-height:120px;margin: 0 auto;">
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.ingreso.show', ['ingreso' => $ingreso->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    let defaultImge = @json(asset('images/default.jpg'));

    $(document).ready(function(){
      $('#proveedor').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

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
  </script>
@endsection
