@extends('layouts.app')

@section('title', 'Reporte Inventario')

@section('head')
  <!-- Datepicker -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/plugins/datapicker/datepicker3.css') }}">
@endsection

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Reportes</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Reportes</li>
        <li class="breadcrumb-item active"><strong>Inventario</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center mb-3 no-print">
    <div class="col-md-12 no-print">
      <button class="btn btn-default btn-sm btn-print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
    <div class="col-md-4 no-print">
      <form id="exportForm" action="{{ route('admin.reportes.inventarios.get') }}" method="POST">
        {{ csrf_field() }}
        
        <div class="form-group">
          <div class="input-daterange input-group">
            <input id="inicioExport" type="text" class="form-control" name="inicio" placeholder="yyyy-mm-dd" required>
            <span class="input-group-addon">Hasta</span>
            <input id="finExport" type="text" class="form-control" name="fin" placeholder="yyyy-mm-dd" required>
          </div>
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
          <h5>Inventarios</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="widget yellw-bg no-padding">
                <div class="p-m">
                  <h1 id="total-iventario" class="m-xs">-</h1>

                  <h4 class="font-bold no-margins">
                    TOTAL INVENTARIOS
                  </h4>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="widget yellw-bg no-padding">
                <div class="p-m">
                  <h1 id="total-costo" class="m-xs">-</h1>

                  <h4 class="font-bold no-margins">
                    COSTO TOTAL
                  </h4>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="widget yellw-bg no-padding">
                <div class="p-m">
                  <h1 id="total-items" class="m-xs">-</h1>

                  <h4 class="font-bold no-margins">
                    TOTAL ITEMS
                  </h4>
                </div>
              </div>
            </div><!-- /.col -->
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
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">Agregado</th>
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
    </div>
  </div>
  
@endsection

@section('script')
  <!-- Datepicker -->
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/plugins/datapicker/locales/bootstrap-datepicker.es.min.js') }}"></script>
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
          fin: $('#finExport').val(),
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
      })
      .done(function(inventarios){
        $('#tbody').empty();
          let totalItems = 0,
              totalCosto = 0,
              totalInventario = 0;

        $.each(inventarios, function(i, inventario){
          let clase = ''

          $.each(inventarios, function(j, k){
            if(k.nombre == inventario.nombre && k.valor != inventario.valor){
              clase = 'danger'
            }
          })

          let tr = `<tr class="${clase}">`
          tr += `<td class="text-center">${inventario.tipo}</td>`
          tr += `<td class="text-center">${inventario.nombre}</td>`
          tr += `<td class="text-center">${inventario.valor.toLocaleString('de-DE')}</td>`
          tr += `<td class="text-center">${inventario.fecha}</td>`
          tr += `<td class="text-center">${inventario.cantidad}</td>`
          tr += `<td class="text-center">${inventario.created_at}</td>`
          tr += '</tr>'

          totalItems += +inventario.cantidad
          totalCosto += +inventario.valor
          totalInventario++

          $('#tbody').append(tr)
        })

        $('#total-items').text(totalItems.toLocaleString('de-DE'))
        $('#total-costo').text(totalCosto.toLocaleString('de-DE'))
        $('#total-iventario').text(totalInventario.toLocaleString('de-DE'))
      })
      .fail(function(){
        ALERT.show().delay(7000).hide('slow');
        $('#total-items, #total-costo, #total-iventario').text('-')
        $('#tbody').empty();
      })
      .always(function(){
        BTN.prop('disabled', false);
        IBOX.toggleClass('sk-loading', false);
      })
    }
  </script>
@endsection
