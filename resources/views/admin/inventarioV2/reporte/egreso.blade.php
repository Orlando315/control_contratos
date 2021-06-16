@extends('layouts.app')

@section('title', 'Reporte Egresos - Inventario')

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
      <h2>Reportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.inventario.v2.index') }}">Inventarios V2</a></li>
        <li class="breadcrumb-item active">Egresos</li>
        <li class="breadcrumb-item active"><strong>Reporte</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="no-print mb-3">
    <div class="row justify-content-center mb-3">
      <div class="col-12 no-print">        
        <a class="btn btn-default btn-sm" href="{{ route('admin.inventario.v2.index') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
        <button class="btn btn-default btn-sm btn-print"><i class="fa fa-print"></i> Imprimir</button>
      </div>
    </div>

    <form id="exportForm" action="{{ route('admin.inventario.egreso.reporte') }}" method="POST">
      @csrf

      <div class="row justify-content-center mb-3 no-print">
        <div class="col-md-3">
          <div class="form-group">
            <label for="emisor">Emitido por:</label>
            <select id="emisor" class="form-control" name="emisor">
              <option value="">Seleccione...</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->nombre() }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="user">Usuario:</label>
            <select id="user" class="form-control" name="user">
              <option value="">Seleccione...</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->nombre() }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="inventario">Inventario:</label>
            <select id="inventario" class="form-control" name="inventario">
              <option value="">Seleccione...</option>
              @foreach($inventarios as $inventario)
                <option value="{{ $inventario->id }}">{{ $inventario->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row justify-content-center mb-3">
        <div class="col-md-6">
          <div class="form-group">
            <div class="input-daterange input-group">
              <input id="inicioExport" type="text" class="form-control" name="from" placeholder="yyyy-mm-dd">
              <span class="input-group-addon">Hasta</span>
              <input id="finExport" type="text" class="form-control" name="to" placeholder="yyyy-mm-dd">
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-md-6">
          <button id="search" class="btn btn-primary btn-block btn-sm" type="submit">Buscar</button>
        </div>
      </div>

      <div class="alert alert-danger" style="display: none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong class="text-center">Ha ocurrido un error.</strong> 
      </div>
    </form>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <table class="table table-bordered table-striped table-sm w-100">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Emitido por</th>
                <th>Inventario</th>
                <th>Dirigido a</th>
                <th>Cantidad</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody id="tbody">
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
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
    const IBOX  = $('.ibox-content');
    const ALERT = $('.alert');
    const BTN   = $('#search');

    $(document).ready(function(){
      $('.input-daterange').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false
      });

      $('#emisor, #user, #inventario').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      $('#exportForm').submit(getEvents);
    })

    function getEvents(e){
      e.preventDefault();

      let form = $(this),
          action = form.attr('action');

      BTN.prop('disabled', true);
      ALERT.hide();
      IBOX.toggleClass('sk-loading', true);

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        dataType: 'json',
      })
      .done(function(egresos) {
        $('#tbody').empty();

        if(egresos.length > 0){
          $.each(egresos, function(i, egreso){
            let tr = $('<tr></tr>');

            tr.append(`<td class="text-center">${i+1}</td>`);
            tr.append(`<td>${egreso.emiditoPor ? egreso.emitidoPor.nombres : ''} ${egreso.emitidoPor ? egreso.emitidoPor.apellidos : ''}</td>`);
            tr.append(`<td>${egreso.inventario.nombre}</td>`);
            tr.append(`<td>${egreso.user.nombres} ${egreso.user.apellidos}</td>`);
            tr.append(`<td class="text-center">${egreso.cantidad}</td>`);
            tr.append(`<td>${new Date(egreso.created_at).toLocaleString('ES')}</td>`);

            $('#tbody').append(tr);
          });
        }else{
          $('#tbody').append('<tr><td colspan="6" class="text-center text-muted">No se encontraron resultados</td></tr>');
        }
      })
      .fail(function(){
        ALERT.show().delay(7000).hide('slow');
      })
      .always(function(){
        BTN.prop('disabled', false);
        IBOX.toggleClass('sk-loading', false);
      })
    }
 	</script>
@endsection
