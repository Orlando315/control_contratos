@extends('layouts.app')

@section('title', 'Perfil - '.config('app.name'))
@section('header', 'Perfil')
@section('breadcrumb')
	<ol class="breadcrumb">
	  <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
	  <li class="active"> Perfil </li>
	</ol>
@endsection

@section('content')
  <section>
    <a class="btn btn-flat btn-default" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    @if(Auth::user()->tipo == 1)
      <a class="btn btn-flat btn-success" href="{{ route('empresas.edit') }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    @else
      <a class="btn btn-flat btn-success" href="{{ route('usuarios.editPerfil') }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    @endif
    <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock" aria-hidden="true"></i> Cambiar contraseña</button>

  </section>

  <section style="margin-top: 20px">

    @include('partials.flash')

    @if (count($errors) > 0)
    <div class="alert alert-danger alert-important">
      <ul>
        @foreach($errors->all() as $error)
           <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <div class="row">
      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-body box-profile">
            <h3 class="profile-username text-center">{{ Auth::user()->usuario }}</h3>
            <p class="text-muted text-center">{{ Auth::user()->tipo() }}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nombres</b>
                <span class="pull-right">{{ Auth::user()->nombres }}</span>
              </li>
              @if(Auth::user()->tipo != 1)
              <li class="list-group-item">
                <b>Apellidos</b>
                <span class="pull-right">{{ Auth::user()->apellidos }}</span>
              </li>
              @endif
              <li class="list-group-item">
                <b>RUT</b>
                <span class="pull-right">{{ Auth::user()->rut }}</span>
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <span class="pull-right">{{ Auth::user()->email }}</span>
              </li>
              @if(Auth::user()->tipo == 1)
              <li class="list-group-item">
                <b>Representante</b>
                <span class="pull-right"> {{ Auth::user()->empresa->representante }} </span>
              </li>
              @endif
              <li class="list-group-item">
                <b>Teléfono</b>
                <span class="pull-right"> {{ Auth::user()->telefono }} </span>
              </li>
              @if(Auth::user()->tipo == 1)
              <li class="list-group-item">
                <b>Jornada</b>
                <span class="pull-right">{{ Auth::user()->empresa->configuracion->jornada }}</span>
              </li>
              @endif
              @if(Auth::user()->tipo == 1)
              <li class="list-group-item">
                <b>Días antes del vencimiento</b>
                <span class="pull-right">{{ Auth::user()->empresa->configuracion->dias_vencimiento }}</span>
              </li>
              @endif
            </ul>
          </div><!-- /.box-body -->
        </div>
      </div>

      <div class="col-md-3">
        <div class="box box-danger">
          <div class="box-header text-center">
            <h3 class="box-title">Logo</h3>
          </div>
          <div class="box-body">
            <img class="img-responsive" src="{{ Auth::user()->empresa->logo_url }}" alt="Logo" style="max-height: 180px;margin: 0 auto;">
          </div>
        </div>
      </div>    
    </div>
  </section>


  <div id="passModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="passModalLabel">Cambiar contraseña</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <form class="col-md-8 col-md-offset-2" action="{{ route('perfil.password') }}" method="POST">
              {{ method_field('PATCH') }}
              {{ csrf_field() }}
              <div class="form-group">
                <label>Contraseña nueva: *</label>
                <input id="password" class="form-control" type="password" pattern=".{6,}" name="password" required>
                <p class="help-block">Debe contener al menos 6 caracteres.</p>
              </div>
              <div class=" form-group">
                <label>Verificar: *</label>
                <input id="password_confirmation" class="form-control" type="password" pattern=".{6,}" name="password_confirmation" required>
                <p class="help-block">Debe contener al menos 6 caracteres.</p>
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

              <center>
                <button class="btn btn-flat btn-danger" type="submit">Guardar</button>
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
              </center>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    $(document).ready(function(){
        $("#pp").click(function(event) {
        var bool = this.checked;
        if(bool === true){
          $("#password_fields").show();
          $("#password,#password_confirmation").prop('required',true);
        }else{
          $("#password_fields").hide();
          $("#password,#password_confirmation").prop('required',false).val('');
        }
      });
    });
  </script>
@endsection
