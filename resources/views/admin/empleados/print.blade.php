@extends('layouts.blank')

@section('title', 'Empleado')

@section('head')
  <!-- App css -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
  <div class="container">
    <div class="row mb-3 no-print">
      <div class="col-12">
        <a class="btn btn-default btn-sm" href="{{ route('admin.empleados.show', ['empleado' => $empleado->id]) }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-12">
        <h2>Ficha de empleado</h2>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-3">
        <div class="ibox">
          <div class="ibox-title px-3">
            <h5>Datos del Empleado</h5>
          </div>
          <div class="ibox-content no-padding">
            <ul class="list-group">
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">{{ $empleado->contrato->nombre }}</span>
              </li>
              <li class="list-group-item">
                <b>Usuario</b>
                <span class="pull-right">{{ $empleado->usuario->usuario }}</span>
              </li>
              <li class="list-group-item">
                <b>Nombres</b>
                <span class="pull-right">{{ $empleado->usuario->nombres }}</span>
              </li>
              <li class="list-group-item">
                <b>Apellidos</b>
                <span class="pull-right">{{ $empleado->usuario->apellidos }}</span>
              </li>
              <li class="list-group-item">
                <b>Sexo</b>
                <span class="pull-right">{{ $empleado->sexo }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha de nacimiento</b>
                <span class="pull-right">{{ $empleado->fecha_nacimiento }}</span>
              </li>
              <li class="list-group-item">
                <b>RUT</b>
                <span class="pull-right"> {{ $empleado->usuario->rut }} </span>
              </li>
              <li class="list-group-item">
                <b>Dirección</b>
                <span class="pull-right"> {{ $empleado->direccion }} </span>
              </li>
              <li class="list-group-item">
                <b>Teléfono</b>
                <span class="pull-right">@nullablestring($empleado->usuario->telefono)</span>
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <span class="pull-right">@nullablestring($empleado->usuario->email)</span>
              </li>
              <li class="list-group-item">
                <b>Profesión</b>
                <span class="pull-right">@nullablestring($empleado->profesion)</span>
              </li>
              <li class="list-group-item">
                <b>Talla de camisa</b>
                <span class="pull-right">@nullablestring($empleado->talla_camisa)</span>
              </li>
              <li class="list-group-item">
                <b>Talla de zapato</b>
                <span class="pull-right">@nullablestring($empleado->talla_zapato)</span>
              </li>
              <li class="list-group-item">
                <b>Talla de pantalon</b>
                <span class="pull-right">@nullablestring($empleado->talla_pantalon)</span>
              </li>
              <li class="list-group-item text-center">
                <small class="text-muted">{{ optional($empleado->created_at)->format('d-m-Y H:i:s') }}</small>
              </li>
            </ul>
          </div><!-- /.ibox-content -->
        </div>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-4">
            <div class="ibox">
              <div class="ibox-title px-3">
                <h5>Contrato</h5>
              </div>
              <div class="ibox-content no-padding">
                <ul class="list-group">
                  <li class="list-group-item">
                    <b>Jornada</b>
                    <span class="pull-right">{{ $empleado->lastContrato->jornada }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Sueldo</b>
                    <span class="pull-right">{{ $empleado->lastContrato->sueldo() }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Inicio</b>
                    <span class="pull-right">{{ $empleado->lastContrato->inicio }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Inicio de Jornada</b>
                    <span class="pull-right"> {{$empleado->lastContrato->inicio_jornada}} </span>
                  </li>
                  <li class="list-group-item">
                    <b>Fin</b>
                    <span class="pull-right"> {!! $empleado->lastContrato->fin ?? '<span class="text-muted">Indefinido</span>' !!} </span>
                  </li>
                  <li class="list-group-item">
                    <b>Descripción</b>
                    <span class="pull-right">@nullablestring($empleado->lastContrato->descripcion)</span>
                  </li>
                </ul>
              </div><!-- /.ibox-content -->
            </div>
          </div>
          <div class="col-md-4">
            <div class="ibox">
              <div class="ibox-title px-3">
                <h5>Datos Bancarios</h5>
              </div>
              <div class="ibox-content no-padding">
                <ul class="list-group">
                  <li class="list-group-item">
                    <b>Banco</b>
                    <span class="pull-right">{{ $empleado->banco->nombre }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Tipo de cuenta</b>
                    <span class="pull-right">{{ $empleado->banco->tipo_cuenta }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Cuenta</b>
                    <span class="pull-right"> {{ $empleado->banco->cuenta }} </span>
                  </li>
                </ul>
              </div><!-- /.ibox-content -->
            </div>    
          </div>
          <div class="col-md-4">
            <div class="ibox">
              <div class="ibox-title px-3">
                <h5>Contacto de emergencia</h5>
              </div>
              <div class="ibox-content no-padding">
                <ul class="list-group">
                  <li class="list-group-item">
                    <b>Nombre</b>
                    <span class="pull-right">@nullablestring($empleado->nombre_emergencia)</span>
                  </li>
                  <li class="list-group-item">
                    <b>Teléfono</b>
                    <span class="pull-right">@nullablestring($empleado->telefono_emergencia)</span>
                  </li>
                </ul>
              </div><!-- /.ibox-content -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <p class="text-center text-muted">{{ config('app.name') }} - {{ date('Y') }}</p>
  </div>
@endsection

@section('script')
 	<script type="text/javascript">
    setTimeout(function () { window.print(); }, 500);
    window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
 	</script>
@endsection
