@extends( 'layouts.app' )
@section( 'title', 'Sueldos - '.config( 'app.name' ) )
@section( 'header','Sueldos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}">Sueldos</a></li>
    <li class="active">Agregar</li>
  </ol>
@endsection
@section('content')
  <!-- Formulario -->
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <form id="form-pagos" action="{{ route('sueldos.store', ['contrato' => $contrato->id]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <h4>Realizar pagos</h4>
        
        <p><b>Contrato:</b> {{ $contrato->nombre }}</p>
        <p><b>Empleados:</b> {{ $contrato->empleados()->count() }}</p>
        <p><b>Mes a pagar:</b>
          <span class="text-danger">
            @if($paymentMonth)
              {{ $paymentMonth }}
            @else
              Ya existen pagos registrados este mes.
            @endif
          </span>
        </p>
        <p><b>Total a pagar:</b> {{ number_format($contrato->getTotalAPagar(), 2, ',','.') }}</p>
        <hr>

        <fieldset>
          <legend style="border-bottom: none">Empleados</legend>
          <table class="table table-sm table-condensed table-anticipos">
            <thead>
              <tr>
                <td>Empleado</td>
                <td>Pago</td>
                <td>Adjunto</td>
                <td></td>
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
                <td class="form-inline">
                  <div class="form-group">
                    <input id="empleado[{{$empleado->id}}]" name="empleado[{{$empleado->id}}]" type="file" accept="image/jpeg,image/png,application/pdf,text/plain,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
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

        <div id="errorCheck" class="alert alert-danger alert-important" style="display: none">
          Debe marcar a todos los empleados antes de continuar.
        </div>

        @if (count($errors) > 0)
        <div class="alert alert-danger alert-important">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>  
        </div>
        @endif

        <div class="form-group text-right">
          <a class="btn btn-flat btn-default" href="{{ route('sueldos.index', ['contrato' => $contrato->id]) }}"><i class="fa fa-reply"></i> Atras</a>
          @if($paymentMonth)
          <button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-send"></i> Guardar</button>
          @endif
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
  $(document).ready( function(){
    $('#fecha').datepicker({
      format: 'dd-mm-yyyy',
      language: 'es',
      keyboardNavigation: false,
      autoclose: true
    });

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
        $('#errorCheck').show().delay(7000).hide('slow');
      }
    })
  });
</script>
@endsection
