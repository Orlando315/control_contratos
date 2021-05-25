@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('perfil.edit') }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
      <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#passModal"><i class="fa fa-lock" aria-hidden="true"></i> Cambiar contraseña</button>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            @role('empleado')
              <li class="list-group-item">
                <b>Contrato</b>
                <span class="pull-right">
                  {{ Auth::user()->empleado->contrato->nombre }}
                </span>
              </li>
              <li class="list-group-item">
                <b>Roles</b>
                <span class="pull-right">{!! Auth::user()->allRolesNames() !!}</span>
              </li>
            @endrole
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ Auth::user()->nombres }}</span>
            </li>
            <li class="list-group-item">
              <b>Apellidos</b>
              <span class="pull-right">@nullablestring(Auth::user()->apellidos)</span>
            </li>
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ Auth::user()->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring(Auth::user()->email)</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring(Auth::user()->telefono)</span>
            </li>
            @role('empleado')
              <li class="list-group-item">
                <b>Sexo</b>
                <span class="pull-right">{{ Auth::user()->empleado->sexo }}</span>
              </li>
              <li class="list-group-item">
                <b>Fecha de nacimiento</b>
                <span class="pull-right">{{ Auth::user()->empleado->fecha_nacimiento }}</span>
              </li>
              <li class="list-group-item">
                <b>Dirección</b>
                <span class="pull-right">{{ Auth::user()->empleado->direccion }}</span>
              </li>
              <li class="list-group-item">
                <b>Profesión</b>
                <span class="pull-right">@nullablestring(Auth::user()->empleado->profesion)</span>
              </li>
              <li class="list-group-item">
                <b>Talla de camisa</b>
                <span class="pull-right">@nullablestring(Auth::user()->empleado->talla_camisa)</span>
              </li>
              <li class="list-group-item">
                <b>Talla de zapato</b>
                <span class="pull-right">@nullablestring(Auth::user()->empleado->talla_zapato)</span>
              </li>
              <li class="list-group-item">
                <b>Talla de pantalon</b>
                <span class="pull-right">@nullablestring(Auth::user()->empleado->talla_pantalon)</span>
              </li>
            @endrole
          </ul>
        </div>
      </div>
    </div>

    @role('empleado')
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-4">
            <div class="ibox">
              <div class="ibox-title px-3">
                <h5>Contrato</h5>
                <div class="ibox-tools">
                  <button class="btn btn-default btn-xs" title="Ver historial" data-toggle="modal" data-target="#historyModal"><i class="fa fa-list"></i></button>
                </div>
              </div>  
              <div class="ibox-content no-padding">
                <ul class="list-group">
                  <li class="list-group-item">
                    <b>Jornada</b>
                    <span class="pull-right">{{ Auth::user()->empleado->lastContrato->jornada }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Sueldo</b>
                    <span class="pull-right">{{ Auth::user()->empleado->lastContrato->sueldo() }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Inicio</b>
                    <span class="pull-right">{{ Auth::user()->empleado->lastContrato->inicio }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Inicio de Jornada</b>
                    <span class="pull-right">{{Auth::user()->empleado->lastContrato->inicio_jornada}}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Fin</b>
                    <span class="pull-right">{!! Auth::user()->empleado->lastContrato->fin ?? '<span class="text-muted">Indefinido</span>' !!}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Descripción</b>
                    <span class="pull-right">@nullablestring(Auth::user()->empleado->lastContrato->descripcion)</span>
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
                    <span class="pull-right">{{ Auth::user()->empleado->banco->nombre }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Tipo de cuenta</b>
                    <span class="pull-right">{{ Auth::user()->empleado->banco->tipo_cuenta }}</span>
                  </li>
                  <li class="list-group-item">
                    <b>Cuenta</b>
                    <span class="pull-right">{{ Auth::user()->empleado->banco->cuenta }}</span>
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
                    <span class="pull-right">@nullablestring(Auth::user()->empleado->nombre_emergencia)</span>
                  </li>
                  <li class="list-group-item">
                    <b>Teléfono</b>
                    <span class="pull-right">@nullablestring(Auth::user()->empleado->telefono_emergencia)</span>
                  </li>
                </ul>
              </div><!-- /.ibox-content -->
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-12">
            <div class="tabs-container">
              <ul class="nav nav-tabs">
                <li><a class="nav-link active" href="#tab-11" data-toggle="tab"><i class="fa fa-paperclip"></i> Adjuntos</a></li>
                <li><a class="nav-link" href="#tab-12" data-toggle="tab"><i class="fa fa-file-text-o"></i> Documentos</a></li>
                <li><a class="nav-link" href="#tab-14" data-toggle="tab"><i class="fa fa-archive"></i> Solicitudes</a></li>
              </ul>
              <div class="tab-content">
                <div id="tab-11" class="tab-pane active">
                  <div class="panel-body">
                    <div class="row icons-box icons-folder">
                      @foreach(Auth::user()->empleado->carpetas as $carpeta)
                        <div class="col-md-3 col-xs-4 infont mb-3">
                          <a href="{{ route('carpeta.show', ['carpeta' => $carpeta->id]) }}">
                            @if($carpeta->isRequisito())
                              <span class="pull-left text-muted" title="Requisito"><i class="fa fa-asterisk" aria-hidden="true" style="font-size: 12px"></i></span>
                            @endif
                            <i class="fa fa-folder" aria-hidden="true"></i>
                            <p class="m-0">{{ $carpeta->nombre }}</p>
                          </a>
                        </div>
                      @endforeach
                    </div>

                    <hr class="hr-line-dashed">

                    <div class="row">
                      @forelse(Auth::user()->empleado->documentos as $documento)
                        @include('partials.documentos', ['edit' => false])
                      @empty
                        <div class="col-12">
                          <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
                        </div>
                      @endforelse
                    </div>
                  </div>
                </div>
                <div id="tab-12" class="tab-pane">
                  <div class="panel-body">
                    <table class="table data-table table-bordered table-hover table-sm w-100">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Documento</th>
                          <th class="text-center">Padre</th>
                          <th class="text-center">Caducidad</th>
                          <th class="text-center">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach(Auth::user()->empleado->plantillaDocumentos as $plantillaDocumento)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>@nullablestring($plantillaDocumento->nombre)</td>
                            <td>@nullablestring(optional($plantillaDocumento->padre)->nombre)</td>
                            <td class="text-center">@nullablestring(optional($plantillaDocumento->caducidad)->format('d-m-Y'))</td>
                            <td class="text-center">
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  <li>
                                    <a class="dropdown-item" href="{{ route('plantilla.documento.show', ['documento' => $plantillaDocumento->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="tab-14" class="tab-pane">
                  <div class="panel-body">
                    <table class="table data-table table-bordered table-hover table-sm w-100">
                      <thead>
                        <tr class="text-center">
                          <th>#</th>
                          <th>Tipo</th>
                          <th>Descripción</th>
                          <th>Estatus</th>
                          <th>Adjunto</th>
                          <th>Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach(Auth::user()->empleado->solicitudes as $solicitud)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $solicitud->tipo() }}</td>
                            <td>@nullablestring($solicitud->descripcion)</td>
                            <td class="text-center">{!! $solicitud->status() !!}</td>
                            <td class="text-center">
                              @if($solicitud->adjunto)
                                <a href="{{ $solicitud->download }}" title="Descargar adjunto">Descargar</a>
                              @else
                                @nullablestring(null)
                              @endif
                            </td>
                            <td class="text-center">
                              <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false"><i class="fa fa-cogs"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-start">
                                  <li>
                                    <a class="dropdown-item" href="{{ route('solicitud.show', ['solicitud' => $solicitud->id]) }}">
                                      <i class="fa fa-search"></i> Ver
                                    </a>
                                  </li>
                                </ul>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endrole
  </div>

  <div id="passModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="{{ route('perfil.password') }}" method="POST">
          @method('PATCH')
          @csrf

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title">Cambiar contraseña</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="password">Contraseña nueva: *</label>
              <input id="password" class="form-control" type="password" pattern=".{6,}" name="password" required>
              <p class="form-text">Debe contener al menos 6 caracteres.</p>
            </div>

            <div class=" form-group">
              <label for="password_confirmation">Verificar: *</label>
              <input id="password_confirmation" class="form-control" type="password" pattern=".{6,}" name="password_confirmation" required>
              <p class="form-text">Debe contener al menos 6 caracteres.</p>
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
          </div>
          <div class="modal-footer">
            <button class="btn btn-default btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-warning btn-sm" type="submit">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @role('empleado')
    <div id="historyModal" class="modal inmodal fade" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
            </button>
            <h4 class="modal-title" id="historyModalLabel">Historial de contratos</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              @foreach(Auth::user()->empleado->contratos as $contrato)
                <div class="col-md-4">
                  <div class="ibox">
                    <div class="ibox-content no-padding">
                      <ul class="list-group mb-3">
                        <li class="list-group-item">
                          <b>Creado</b>
                          <span class="pull-right">{{ optional($contrato->created_at)->format('d-m-Y H:i:s') }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>Jornada</b>
                          <span class="pull-right">{{ $contrato->jornada }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>Sueldo</b>
                          <span class="pull-right">{{ $contrato->sueldo() }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>Inicio</b>
                          <span class="pull-right">{{ $contrato->inicio }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>Inicio de Jornada</b>
                          <span class="pull-right">{{$contrato->inicio_jornada }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>Fin</b>
                          <span class="pull-right">{!! $contrato->fin ?? '<span class="text-muted">Indefinido</span>' !!}</span>
                        </li>
                        <li class="list-group-item">
                          <b>Descripción</b>
                          <span class="pull-right">@nullablestring($contrato->descripcion)</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  @endrole
@endsection
