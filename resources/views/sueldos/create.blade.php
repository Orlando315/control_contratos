@extends('layouts.app')

@section('title', 'Sueldos')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Sueldos</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.index') }}">Contratos</a></li>
        <li class="breadcrumb-item"><a href="{{ route('contratos.show', ['contrato' => $contrato->id]) }}">Contrato</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}">Sueldos</a></li>
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
          <h5>Realizar pagos</h5>          
        </div>
        <div class="ibox-content">
          <form id="form-pagos" action="{{ route('sueldos.store', ['contrato' => $contrato->id]) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <p class="m-0"><strong>Contrato:</strong> {{ $contrato->nombre }}</p>
            <p class="m-0"><strong>Empleados:</strong> {{ $contrato->empleados()->count() }}</p>
            <p class="m-0"><strong>Mes a pagar:</strong>
              <span class="text-danger">
                @if($paymentMonth)
                  {{ $paymentMonth }}
                @else
                  Ya existen pagos registrados este mes.
                @endif
              </span>
            </p>
            <p class="m-0"><strong>Total a pagar:</strong> {{ number_format($contrato->getTotalAPagar(), 2, ',','.') }}</p>
            
            <hr class="hr-line-dashed">
            <fieldset>
              <legend style="border-bottom: none">Empleados</legend>
              <table class="table table-sm table-condensed table-anticipos">
                <thead>
                  <tr>
                    <th>Empleado</th>
                    <th>Pago</th>
                    <th>Adjunto</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="tbody-empleados">
                  @foreach($empleados as $empleado)
                  <tr>
                    <td>
                      <p>{{$empleado->usuario->nombres}} {{$empleado->usuario->apellidos}}</p>
                    </td>
                    <td>
                      <p>{{number_format($empleado->getSueldoLiquido(), 2, ',','.')}}</p>
                    </td>
                    <td>
                      <div class="form-group">
                        <div class="custom-file">
                          <input id="empleado-{{ $empleado->id }}" class="custom-file-input" type="file" name="empleado[{{ $empleado->id }}]" data-msg-placeholder="Seleccionar" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                          <label class="custom-file-label" for="empleado-{{ $empleado->id }}">Seleccionar</label>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="checkbox">
                        <label class="container-checkbox">
                          <input type="checkbox">
                          <span class="checkmark-check"></span>
                        </label>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </fieldset>

            <div class="alert alert-danger alert-important"{!! (count($errors) > 0) ? '' : ' style="display:none;"' !!}>
              <ul class="m-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              @if($paymentMonth)
                <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready( function(){
      $('#tbody-empleados').on('click', 'input[type="checkbox"]', function(){
        if($(this).is(':checked')){
          $(this).closest('div.checkbox').removeClass('has-error');
        }
      })

      $('#form-pagos').submit(function(e){
        e.preventDefault();
        let checkboxs = $('#tbody-empleados input[type="checkbox"]');
        let counter = 0;
        $.each(checkboxs, function(k, v){
          if(!$(v).is(':checked')){
            counter++
          }

          $(v).closest('div.checkbox').toggleClass('has-error', !$(v).is(':checked'));
        })

        if(counter == 0){
          e.currentTarget.submit();
        }else{
          showAlert('Debe marcar a todos los empleados antes de continuar.')
        }
      })

      $('.custom-file-input').change(function () {
        if(this.files && this.files[0]){
          let file = this.files[0];

          if([
            'image/png',
            'image/jpeg',
            'text/plain',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ]
            .includes(file.type)) {
            changeLabel($(this).attr('id'), file.name)
          }else{
            changeLabel($(this).attr('id'), 'Seleccionar', true)
            showAlert('El archivo no es de un tipo admitido.')
          }
        }
      })
    });

    // Cambiar el nombre del label del input file, y colocar el nombre del archivo
    function changeLabel(id, name, clear = false){
      $(`#${id}`).siblings(`label[for="${id}"]`).text(name);

      if(clear){
        $(`#${id}`).val('') 
      }
    }

    function showAlert(error = 'Ha ocurrido un error'){
      $('.alert ul').empty().append(`<li>${error}</li>`)
      $('.alert').show().delay(5000).hide('slow')
    }
</script>
@endsection
