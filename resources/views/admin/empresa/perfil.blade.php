@extends('layouts.app')

@section('title', 'Empresa')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Empresa</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Empresa</li>
        <li class="breadcrumb-item active"><strong>Perfil</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-md-12">
      <a class="btn btn-default btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-default btn-sm" href="{{ route('admin.empresa.edit') }}"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-info"></i> Información</h5>
        </div>
        @if($empresa->logo)
          <div class="ibox-content no-padding text-center border-left-right">
            <img class="img-fluid" src="{{ $empresa->logo_url }}" alt="Logo" style="max-height: 180px;margin: 0 auto;">
          </div>
        @endif
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>RUT</b>
              <span class="pull-right">{{ $empresa->rut }}</span>
            </li>
            <li class="list-group-item">
              <b>Razón social</b>
              <span class="pull-right">{{ $empresa->nombre }}</span>
            </li>
            <li class="list-group-item">
              <b>Representante</b>
              <span class="pull-right">{{ $empresa->representante }}</span>
            </li>
            <li class="list-group-item">
              <b>Teléfono</b>
              <span class="pull-right">@nullablestring($empresa->telefono)</span>
            </li>
            <li class="list-group-item">
              <b>Email</b>
              <span class="pull-right">@nullablestring($empresa->email)</span>
            </li>
            <li class="list-group-item">
              <b>Jornada</b>
              <span class="pull-right">@nullablestring($empresa->configuracion->jornada)</span>
            </li>
            <li class="list-group-item">
              <b>Días antes del vencimiento</b>
              <span class="pull-right">@nullablestring($empresa->configuracion->dias_vencimiento)</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $empresa->created_at }}</small>
            </li>
          </ul>
        </div>
      </div>
    </div>

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
                    @if($empresa->configuracion->sii_clave)
                      <span class="copy-clipboard label" data-toggle="tooltip" title="¡Haz click para copiar!">
                        {{ $empresa->configuracion->sii_clave }}
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
                    @if($empresa->configuracion->sii_clave_certificado)
                      <span class="copy-clipboard label" data-toggle="tooltip" title="¡Haz click para copiar!">
                        {{ $empresa->configuracion->sii_clave_certificado }}
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
                    @if($empresa->configuracion->firma)
                      <span class="copy-clipboard label" data-toggle="tooltip" title="¡Haz click para copiar!">
                        {{ $empresa->configuracion->firma }}
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
  </div>
@endsection

@section('script')
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
@endsection
