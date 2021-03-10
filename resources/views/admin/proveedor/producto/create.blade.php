@extends('layouts.app')

@section('title', 'Productos')

@section('head')
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Productos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.proveedor.index') }}">Proveedores</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.proveedor.show', ['proveedor' =>  $proveedor->id]) }}">Productos</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="ibox">
        <div class="ibox-title">
          <h4>Agregar Producto</h4>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.proveedor.producto.store', ['proveedor' => $proveedor->id]) }}" method="POST">
            @csrf()

            <div class="form-group{{ $errors->has('inventario') ? ' has-error' : '' }}">
              <label for="inventario">Inventario: *</label>
              <select id="inventario" class="form-control" name="inventario" required>
                <option value="">Seleccione...</option>
                @foreach($inventarios as $inventario)
                  <option value="{{ $inventario->id }}"{{ old('inventario') == $inventario->id ? ' selected' : '' }}>
                    {{ $inventario->nombre }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="form-group{{ $errors->has('nombre') ? ' has-error' : '' }}">
              <label for="nombre">Nombre: *</label>
              <input id="nombre" class="form-control" type="text" name="nombre" maxlength="100" value="{{ old('nombre') }}" placeholder="Nombre" required>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('costo') ? ' has-error' : '' }}">
                  <label for="costo">Costo: *</label>
                  <input id="costo" class="form-control" type="number" name="costo" min="0" max="999999999" step="0.01" value="{{ old('costo') }}" placeholder="Costo" required>
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
              <a class="btn btn-default btn-sm" href="{{ route('admin.proveedor.show', ['proveedor' => $proveedor->id]) }}"><i class="fa fa-reply"></i> Atras</a>
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
    $(document).ready(function(){
      $('#inventario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      $('#inventario').change(selectInventario);
    });

    function selectInventario(){
      let inventario = $(this).val();

      if(!inventario){
        $('#nombre').val('');
        return;
      }

      let option = $(this).find(`option[value="${inventario}"]`);

      $('#nombre').val(option.text().trim());
    }
  </script>
@endsection
