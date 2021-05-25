@extends('layouts.app')

@section('title', 'Carpeta')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Carpetas</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ $carpeta->backUrl }}">Carpeta</a></li>
        <li class="breadcrumb-item active"><strong>Carpeta</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3">
    <div class="col-12">
      <a class="btn btn-default btn-sm" href="{{ $carpeta->backUrl }}"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-3">
      <div class="ibox">
        <div class="ibox-content no-padding">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>Nombre</b>
              <span class="pull-right">{{ $carpeta->nombre }}</span>
            </li>
            <li class="list-group-item text-center">
              <small class="text-muted">{{ $carpeta->created_at }}</small>
            </li>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-9 p-0">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Adjuntos</h5>
        </div>
        <div class="ibox-content">
          <div class="row icons-box icons-folder">
            @foreach($carpeta->subcarpetas as $subcarpeta)
              <div class="col-md-3 col-xs-4 infont mb-3">
                <a href="{{ route('carpeta.show', ['carpeta' => $subcarpeta->id]) }}">
                  @if($subcarpeta->isRequisito())
                    <span class="pull-left text-muted" title="Requisito"><i class="fa fa-asterisk" aria-hidden="true" style="font-size: 12px"></i></span>
                  @endif
                  <i class="fa fa-folder" aria-hidden="true"></i>
                  <p class="m-0">{{ $subcarpeta->nombre }}</p>
                </a>
              </div>
            @endforeach
          </div>
          <hr class="hr-line-dashed">
          <div class="row">
            @forelse($carpeta->documentos as $documento)
              @include('partials.documentos', ['edit' => true])
            @empty
            <div class="col-12">
              <h4 class="text-center text-muted">No hay documentos adjuntos</h4>
            </div>
            @endforelse
          </div>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>
@endsection

@section('script')
  @include('partials.preview-pdf')
@endsection
