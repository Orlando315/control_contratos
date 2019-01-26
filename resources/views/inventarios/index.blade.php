@extends( 'layouts.app' )
@section( 'title','Inventarios - '.config( 'app.name' ) )
@section( 'header','Inventarios' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Inventarios</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-cubes"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Inventarios</span>
          <span class="info-box-number">{{ count($inventarios) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-cubes"></i> Inventarios</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('inventarios.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Inventario</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Valor</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($inventarios as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td>{{ $d->tipo() }}</td>
                  <td>{{ $d->nombre }}</td>
                  <td>{{ $d->valor }}</td>
                  <td>{{ $d->fecha }}</td>
                  <td>{{ $d->cantidad() }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('inventarios.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('inventarios.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
