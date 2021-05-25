@extends('layouts.app')

@section('title', 'Logs')

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
  <div class="row mb-3">
    @foreach($logs as $log)
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
    @endforeach
  </div>

  <div class="row">
    <div class="col-12">
      {{ $logs->links() }}
    </div>
  </div>
@endsection
