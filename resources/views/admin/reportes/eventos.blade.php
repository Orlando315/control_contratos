@extends('layouts.app')

@section('title', 'Reporte Eventos')

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
        <li class="breadcrumb-item">Reportes</li>
        <li class="breadcrumb-item active"><strong>Eventos</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center mb-3 no-print">
    <div class="col-12 no-print">
      <button class="btn btn-default btn-sm btn-print"><i class="fa fa-print"></i> Imprimir</button>
    </div>
    <div class="col-md-4 no-print">
      <form id="exportForm" action="{{ route('admin.reportes.eventos.get') }}" method="POST">
        {{ csrf_field() }}

        <div class="form-group">
          <div class="input-daterange input-group">
            <input id="inicioExport" type="text" class="form-control" name="inicio" placeholder="yyyy-mm-dd" required>
            <span class="input-group-addon">Hasta</span>
            <input id="finExport" type="text" class="form-control" name="fin" placeholder="yyyy-mm-dd" required>
          </div>
        </div>

        <div class="form-group">
          <label for="contrato">Contrato: * </label>
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
          <h5>Eventos</h5>
        </div>
        <div class="ibox-content">
          <div class="sk-spinner sk-spinner-double-bounce">
            <div class="sk-double-bounce1"></div>
            <div class="sk-double-bounce2"></div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm w-100">
              <thead>
                <tr>
                  <th>Empleado</th>
                  <th>Balance</th>
                  <th>Asistencia</th>
                  <th>Descanso</th>
                  <th>Licencia médica</th>
                  <th>Vacaciones</th>
                  <th>Permiso</th>
                  <th>Permiso no remunerable</th>
                  <th>Despido</th>
                  <th>Renuncia</th>
                  <th>Inasistencia</th>
                  <th>Reemplazo</th>
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
      .done(function(resposne) {
        $('#tbody').empty();
        $.each(resposne.data, function(i, empleado){
          let tr = $('<tr></tr>')
          if(i > 0){
            $.each(empleado, function(k, v){
              let background = (k == 1 && v == resposne.days) ? 'success' : ''

              tr.append($(`<td class="${background}"></td>`).text(v))
            })

            $('#tbody').append(tr)
          }
        })
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
