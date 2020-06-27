@extends('layouts.app')

@section('title', 'Anticipos')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/select2/select2-bootstrap4.min.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Anticipos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('anticipos.index') }}">Anticipos</a></li>
        <li class="breadcrumb-item active"><strong>Agregar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Agregar anticipo individual</h5>          
        </div>
        <div class="ibox-content">
          <form action="{{ route('anticipos.store') }}" method="POST">
            {{ csrf_field() }}

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('contrato') ? ' has-error' : '' }}">
                  <label for="contrato">Contrato: *</label>
                  <select id="contrato" class="form-control" name="contrato" required>
                    <option value="">Seleccione...</option>
                    @foreach($contratos as $contrato)
                      <option value="{{ $contrato->id }}"{{ old('contrato') == $contrato->id ? ' selected' : '' }}>{{ $contrato->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('empleado_id') ? ' has-error' : '' }}">
                  <label for="empleado_id">Empleado: *</label>
                  <select id="empleado_id" class="form-control" name="empleado_id" disabled required>
                    <option value="">Seleccione...</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('fecha') ? ' has-error' : '' }}">
                  <label for="fecha">Fecha: *</label>
                  <input id="fecha" class="form-control" type="text" name="fecha" value="{{ old('fecha') }}" placeholder="dd-mm-yyyy" required>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group{{ $errors->has('anticipo') ? ' has-error' : '' }}">
                  <label for="anticipo">Anticipo: *</label>
                  <input id="anticipo" class="form-control" type="number" step="1" min="1" maxlength="999999" name="anticipo" value="{{ old('anticipo') }}" placeholder="Anticipo" required>
                </div>
              </div>
            </div>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>  
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ url()->previous() }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
  <!-- Select2 -->
  <script type="text/javascript" src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      $('#fecha').datepicker({
        format: 'dd-mm-yyyy',
        language: 'es',
        endDate: 'today',
        keyboardNavigation: false,
        autoclose: true
      });

      $('#contrato').change(getEmpleados)
      $('#contrato').change()

      $('#contrato, #empleado_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      })
    });

    function getEmpleados(){
      let contrato = $(this).val(),
          select = $('#empleado_id');

      if(contrato == '') return;

      $.ajax({
        type: 'POST',
        url: '{{ route("empleados.index") }}/contratos/' + contrato,
        data: {
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(data){
        select.empty().append(new Option('Seleccione...', '', false, false)).trigger('change');
        if(data.length > 0){
          $.each(data, function(k, v){
            select.append(new Option(v.usuario.rut + '|' + v.usuario.nombres + ' ' + v.usuario.apellidos, v.id, false, false)).trigger('change')
          })

          select.prop('disabled', false)
        }else{
          select.prop('disabled', true)
        }
      })
      .fail(function(){
        select.prop('disabled', false)
      });
    }
</script>
@endsection
