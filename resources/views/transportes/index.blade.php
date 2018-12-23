@extends( 'layouts.app' )
@section( 'title','Transportes - '.config( 'app.name' ) )
@section( 'header','Transportes' )
@section( 'breadcrumb' )
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li class="active">Transportes</li>
  </ol>
@endsection

@section( 'content' )
  @include('partials.flash')
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-car"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Transportes</span>
          <span class="info-box-number">{{ count($transportes) }}</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-car"></i> Transportes</h3>
          <span class="pull-right">
            <a class="btn btn-success btn-flat" href="{{ route('transportes.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Transporte</a>
          </span>
        </div>
        <div class="box-body">
          <table class="table data-table table-bordered table-hover" style="width: 100%">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Contrato</th>
                <th class="text-center">Vehiculo</th>
                <th class="text-center">Mantención</th>
                <th class="text-center">Chofer</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody class="text-center">
              @foreach($transportes as $d)
                <tr>
                  <td>{{ $loop->index + 1 }}</td>
                  <td><a href="{{ route('contratos.show', ['contrato' => $d->contrato->id]) }}">{{ $d->contrato->nombre }} </a></td>
                  <td>{{ $d->vehiculo }}</td>
                  <td>{{ $d->fecha_mantencion }}</td>
                  <td>{{ $d->chofer }}</td>
                  <td>
                    <a class="btn btn-primary btn-flat btn-sm" href="{{ route('transportes.show', ['id' => $d->id] )}}"><i class="fa fa-search"></i></a>
                    <a class="btn btn-success btn-flat btn-sm" href="{{ route('transportes.edit', ['id' => $d->id] )}}"><i class="fa fa-pencil"></i></a>
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
