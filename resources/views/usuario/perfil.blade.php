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
          @if(Auth::user()->isEmpresa())
            <div class="text-center py-2">
              <img class="img-responsive" src="{{ Auth::user()->empresa->logo_url }}" alt="Logo" style="max-height: 180px;margin: 0 auto;">
            </div>
          @endif
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombres</b>
              <span class="pull-right">{{ Auth::user()->nombres }}</span>
            </li>
            @if(!Auth::user()->isEmpresa())
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
              <span class="pull-right">@nullablestring(Auth::user()->email)</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring(Auth::user()->telefono)</span>
            </li>
            @if(Auth::user()->isEmpresa())
              <li class="list-group-item">
                <b>Representante</b>
                <span class="pull-right"> {{ Auth::user()->empresa->representante }} </span>
              </li>
              <li class="list-group-item">
                <b>Jornada</b>
                <span class="pull-right">{{ Auth::user()->empresa->configuracion->jornada }}</span>
              </li>
              <li class="list-group-item">
                <b>Días antes del vencimiento</b>
                <span class="pull-right">{{ Auth::user()->empresa->configuracion->dias_vencimiento }}</span>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </div>

    @if(Auth::user()->isEmpresa())
      <div class="col-md-9">
        <div class="ibox mb-3">
          <div class="ibox-title">
            <h5><i class="fa fa-random"></i> Integraciones (API)</h5>
          </div>
          <div class="ibox-content">
            <div class="card mb-3">
              <div class="card-header text-center">
                Facturación Sii
              </div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-md-4 text-right">
                      <strong>Clave Sii:</strong>
                    </div>
                    <div class="col-md-8">
                      @if(Auth::user()->empresa->configuracion->sii_clave)
                        <span class="copy-clipboard label" data-toggle="tooltip" title="¡Haz click para copiar!">
                          {{ Auth::user()->empresa->configuracion->sii_clave }}
                        </span>
                      @else
                        @nullablestring(null)
                      @endif
                    </div>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-md-4 text-right">
                      <strong>Clave certificado digital:</strong>
                    </div>
                    <div class="col-md-8">
                      @if(Auth::user()->empresa->configuracion->sii_clave_certificado)
                        <span class="copy-clipboard label" data-toggle="tooltip" title="¡Haz click para copiar!">
                          {{ Auth::user()->empresa->configuracion->sii_clave_certificado }}
                        </span>
                      @else
                        @nullablestring(null)
                      @endif
                    </div>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-md-4 text-right">
                      <strong>Firma:</strong>
                    </div>
                    <div class="col-md-8">
                      @if(Auth::user()->empresa->configuracion->firma)
                        <span class="copy-clipboard label" data-toggle="tooltip" title="¡Haz click para copiar!">
                          {{ Auth::user()->empresa->configuracion->firma }}
                        </span>
                      @else
                        @nullablestring(null)
                      @endif
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    @endif
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
@endsection

@section('script')
  @if(Auth::user()->isEmpresa())
    <script type="text/javascript">
      $(document).ready(function () {
        $('.copy-clipboard').click(copyToClipboard)

        $('.copy-clipboard').tooltip();
        $('.copy-clipboard').on('hide.bs.tooltip', function() {
          $(this).attr('data-original-title', '¡Haz click para copiar!');
        });
      });

      function copyToClipboard() {
        let $temp = $('<input>');
        $('body').append($temp);
        $temp.val($(this).text().trim()).select();
        document.execCommand('copy');
        $temp.remove();
        $(this).attr('data-original-title', '¡Copiado!').tooltip('show');
      }
    </script>
  @endif
@endsection
