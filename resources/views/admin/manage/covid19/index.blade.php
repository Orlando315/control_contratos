@extends('layouts.app')

@section('title', 'Covid-19')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Covid-19</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Manage</li>
        <li class="breadcrumb-item active"><strong>Covid-19</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row mb-3"> 
    <div class="col-6 col-md-3">
      <div class="ibox ">
        <div class="ibox-title">
          <h5>Preguntas</h5>
        </div>
        <div class="ibox-content">
          <h2><i class="fa fa-heartbeat"></i> {{ count($preguntas) }}</h2>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-12">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-heartbeat"></i> Preguntas</h5>

          <div class="ibox-tools">
            <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.covid19.create') }}">
              <i class="fa fa-plus" aria-hidden="true"></i> Nueva Pregunta
            </a>
          </div>
        </div>
        <div class="ibox-content">
          <table class="table data-table table-bordered table-hover table-sm w-100">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Pregunta</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody>
              @foreach($preguntas as $pregunta)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ $pregunta->pregunta }}</td>
                  <td class="text-center">
                    <a class="btn btn-success btn-xs" href="{{ route('admin.manage.covid19.show', ['pregunta' => $pregunta->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-primary btn-xs" href="{{ route('admin.manage.covid19.edit', ['pregunta' => $pregunta->id] )}}"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
