@extends('layouts.app')

@section('title', 'Logs')

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
      <h2>Logs</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item active"><strong>Logs</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="ibox">
    <div class="ibox-content">
      <form action="{{ route('admin.log.index') }}">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="user">Usuario:</label>
              <select id="user" class="form-control" name="user">
                <option value="">Seleccione...</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}"{{ request()->user == $user->id ? ' selected' : '' }}>{{ $user->nombre() }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="model">Modulo:</label>
              <select id="model" class="form-control" name="model">
                <option value="">Seleccione...</option>
                @foreach($models as $model)
                  <option value="{{ $model['model'] }}"{{ request()->model == $model['model'] ? ' selected' : '' }}>{{ $model['title'] }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="event">Evento:</label>
              <select id="event" class="form-control" name="event">
                <option value="">Seleccione...</option>
                <option value="created"{{ request()->event == 'created' ? ' selected' : '' }}>Creado</option>
                <option value="updated"{{ request()->event == 'updated' ? ' selected' : '' }}>Modificado</option>
                <option value="deleted"{{ request()->event == 'deleted' ? ' selected' : '' }}>Eliminado</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="form-group">
              <div class="input-daterange input-group">
                <input id="inicioExport" type="text" class="form-control" name="from" value="{{ request()->from }}" placeholder="yyyy-mm-dd">
                <span class="input-group-addon">Hasta</span>
                <input id="finExport" type="text" class="form-control" name="to" value="{{ request()->to }}" placeholder="yyyy-mm-dd">
              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-4">
            <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-search"></i> Buscar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="row mb-3">
    @forelse($logs as $log)
      <div class="col-12">
        <div class="ibox collapsed m-0">
          <div class="ibox-title">
            <p class="m-0">
              @if($log->description)
                {!! $log->icon() !!} {!! $log->description !!}
              @else
                {!! $log->icon() !!} El elemento <strong>{{ $log->getLogEventTitle() }} #{{ $log->subject_id }}</strong> ha sido <strong>{{ $log->getTranslatedEvent() }}</strong> por el usuario <strong>{{ $log->user->nombre() }}</strong>
              @endif
              <span class="float-right">{{ $log->created_at->format('d-m-Y H:i:s') }}</span>
            </p>
            <div class="ibox-tools">
              <a class="collapse-link">
                <i class="fa fa-chevron-down"></i>
              </a>
            </div>
          </div>
          <div class="ibox-content">
            <p class="m-0"><strong>Fecha: </strong> {{ $log->created_at->format('d-m-Y H:i:s') }}</p>
            <p class="m-0"><strong>Evento: </strong> {{ ucfirst($log->getTranslatedEvent()) }}</p>
            <p class="m-0">
              <strong>Elemento: </strong>
              @if($log->subject && route_exists('admin.'.$log->getSubjectRouteName().'.show'))
                <a href="{{ $log->subject_url }}">
                  {{ $log->getLogEventTitle() }} #{{ $log->subject_id }}
                </a>
              @else
                {{ $log->getLogEventTitle() }} #{{ $log->subject_id }}
              @endif
            </p>
            <p>
              <strong>Realizado por:</strong>
              @permission('user-view')
                <a href="{{ route('admin.usuario.show', ['usuario' => $log->user_id]) }}">{{ $log->user->nombre() }}</a>
              @else
                {{ $log->user->nombre() }}
              @endpermission
            </p>

            <ul class="list-group">
              @if($log->isUpdate())
                <li class="list-group-item"><h4>Valor original <i class="fa fa-long-arrow-right" aria-hidden="false"></i> Valor actualizado</h4></li>
              @endif

              @foreach(($log->isDeleted() ? $log->old_changes : $log->attributes_changes) as $attribute => $value)
                <li class="list-group-item">
                  <strong>{{ $log->getAttributeTitle($attribute) }}:</strong>
                  @if($log->isUpdate())
                    @nullablestring($log->getOldProperty($attribute)) <i class="fa fa-long-arrow-right" aria-hidden="false"></i>
                  @endif
                  @nullablestring($log->getProperty($attribute))
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <h4 class="text-center text-muted">
          No se han encontrado resultados
        </h4>
      </div>
    @endforelse
  </div>

  <div class="row">
    <div class="col-12">
      {{ $logs->withQueryString()->links() }}
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
    
    $(document).ready(function(){
      $('#user, #model, #event').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccione...',
        allowClear: true,
      });

      $('.input-daterange').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',
        keyboardNavigation: false
      });
    });
  </script>
@endsection
