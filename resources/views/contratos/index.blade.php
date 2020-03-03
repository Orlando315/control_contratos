@extends( 'layouts.app' )
@section( 'title','Contratos - '.config( 'app.name' ) )
@section( 'header','Contratos' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Contratos</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-clipboard"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Contratos</span>
          <span class="info-box-number">{{ count($contratos) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-clipboard"></i> Contratos</h3>
          @if(Auth::user()->tipo < 2)
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('contratos.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Contrato</a>
          </span>
          @endif
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Inicio</th>
                <th class="text-center">Fin</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Empleados</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($contratos as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->inicio }}</td>
                  <td>{{ $d->fin }}</td>
                  <td>{{ $d->valor() }}</td>
                  <td>{{ $d->empleados->count() }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('contratos.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    @if(Auth::user()->tipo < 2)
                      <a class="btn btn-success btn-flat btn-sm" href="{{ route('contratos.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
                    @endif
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
