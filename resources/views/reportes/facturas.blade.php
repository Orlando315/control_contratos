@extends('layouts.app')

@section('title', 'Reporte Facturas')

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
        <li class="breadcrumb-item">Reportes</li>
        <li class="breadcrumb-item active"><strong>Facturas</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center mb-3 no-print">
    <div class="col-12 no-print">
      <button class="btn btn-default btn-flat btn-print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
    <div class="col-md-4 no-print">
      <form id="exportForm" action="{{ route('reportes.facturas.get') }}" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
          <div class="input-daterange input-group">
            <input id="inicioExport" type="text" class="form-control" name="inicio" placeholder="yyyy-mm-dd" required>
            <span class="input-group-addon">Hasta</span>
            <input id="finExport" type="text" class="form-control" name="fin" placeholder="yyyy-mm-dd" required>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label" for="contrato">Contrato: *</label>
          <select id="contrato" class="form-control" name="contrato" required>
            <option value="">Seleccione...</option>
            @foreach($contratos as $contrato)
              <option value="{{ $contrato->id }}" {{ old('contrato') == $contrato->id ? 'selected':'' }}>{{ $contrato->nombre }}</option>
            @endforeach
          </select>
        </div>

        <button id="search" class="btn btn-primary btn-block btn-sm" type="submit">Buscar</button>

        <div class="alert alert-danger" style="display: none">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong class="text-center">Ha ocurrido un error.</strong> 
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Facturas</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-4">
              <div class="widget yellw-bg no-padding">
                <div class="p-m">
                  <h1 id="total-facturas" class="m-xs">-</h1>

                  <h4 class="font-bold no-margins">
                    TOTAL FACTURAS
                  </h4>
                </div>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-4">
              <div class="widget yellw-bg no-padding">
                <div class="p-m">
                  <h1 id="total-ingresos" class="m-xs">-</h1>

                  <h4 class="font-bold no-margins">
                    TOTAL INGRESOS
                  </h4>
                </div>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-4">
              <div class="widget yellw-bg no-padding">
                <div class="p-m">
                  <h1 id="total-egresos" class="m-xs">-</h1>

                  <h4 class="font-bold no-margins">
                    TOTAL EGRESOS
                  </h4>
                </div>
              </div>
            </div>
          </div><!-- /.row -->

          <div class="row">
            <div class="col-12">
              <table class="table table-bordered table-striped table-sm w-100">
                <thead>
                  <tr>
                    <th class="text-center">Tipo</th>
                    <th class="text-center">Nombre</th>
                    <th class="text-center">Valor</th>
                    <th class="text-center">Fecha</th>
                    <th class="text-center">Pago</th>
                  </tr>
                </thead>
                <tbody id="tbody">
                  <tr>
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

      $('#contrato').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
      });

      $('#exportForm').submit(getEvents)
    })

    function getEvents(e){
      e.preventDefault();

      let form   = $(this),
          action = form.attr('action');

      BTN.prop('disabled', true);
      ALERT.hide();
      IBOX.toggleClass('sk-loading', true);

      $.ajax({
        type: 'POST',
        url: action,
        data: {
          inicio: $('#inicioExport').val(),
          contrato: $('#contrato').val(),
          fin: $('#finExport').val(),
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(facturas){
        $('#tbody').empty();
          let totalIngresos = 0,
              totalEgresos = 0,
              totalFacturas = 0;

        $.each(facturas, function(i, factura){
          let tr = '<tr>'
          tr += `<td class="text-center">${factura.tipolabel}</td>`
          tr += `<td class="text-center">${factura.nombre}</td>`
          tr += `<td class="text-center">${factura.valor.toLocaleString('es-ES')}</td>`
          tr += `<td class="text-center">${factura.fecha}</td>`
          tr += `<td class="text-center">${factura.pago}</td>`
          tr += '</tr>'

          if(factura.tipo == 1){
            totalIngresos += +factura.valor
          }else{
            totalEgresos += +factura.valor
          }
          totalFacturas++

          $('#tbody').append(tr)
        })

        $('#total-ingresos').text(totalIngresos.toLocaleString('es-ES'))
        $('#total-egresos').text(totalEgresos.toLocaleString('es-ES'))
        $('#total-facturas').text(totalFacturas.toLocaleString('es-ES'))
      })
      .fail(function(){
        ALERT.show().delay(7000).hide('slow');
        $('#total-ingresos, #total-egresos, #total-facturas').text('-')
        $('#tbody').empty();
      })
      .always(function(){
        BTN.prop('disabled', false);
        IBOX.toggleClass('sk-loading', false);
      })
    }
  </script>
@endsection
