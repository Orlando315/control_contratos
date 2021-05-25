@extends('layouts.app')

@section('title', 'Editar')

@section('page-heading')
  <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
      <h2>Usuarios</h2>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
        <li class="breadcrumb-item">Admin</li>
        <li class="breadcrumb-item"><a href="{{ route('admin.usuario.index') }}">Usuarios</a></li>
        <li class="breadcrumb-item active"><strong>Editar</strong></li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="ibox">
        <div class="ibox-title">
          <h5>Editar usuario</h5>
        </div>
        <div class="ibox-content">
          <form action="{{ route('admin.usuario.update', ['usuario' => $usuario->id]) }}" method="POST">
            @method('PATCH')
            @csrf

            <div class="form-group{{ $errors->has('role') ? ' has-error' : '' }}">
              <label>Role: *</label>
              <div class="row">
                @if($usuario->isEmpresa())
                  <div class="col-md-4">
                    <div class="custom-control custom-radio">
                      <input id="role-empresa" class="custom-control-input" type="radio" name="role" value="empresa"{{ $usuario->hasActiveOrInactiveRole('empresa') ? '' : ' checked' }} required>
                      <label for="role-empresa" class="custom-control-label">Empresa</label>
                    </div>
                  </div>
                @endif

                @foreach($roles as $role)
                  @continue(!Auth::user()->isAdmin() && $role->name == 'administrador')

                  <div class="col-md-4">
                    <div class="custom-control custom-radio">
                      <input id="role-{{ $role->name }}" class="custom-control-input" type="radio" name="role" value="{{ $role->name }}"{{ $usuario->hasActiveOrInactiveRole($role->name) ? ' checked' : '' }} required>
                      <label for="role-{{ $role->name }}" class="custom-control-label">{{ $role->name() }}</label>
                    </div>
                  </div>
                @endforeach

                @if($usuario->isEmpleado())
                  <div class="col-md-4">
                    <div class="custom-control custom-radio">
                      <input id="role-empleado" class="custom-control-input" type="radio" name="role" value="empleado"{{ $usuario->hasActiveOrInactiveRole('administrador|supervisor') ? '' : ' checked' }} required>
                      <label for="role-empleado" class="custom-control-label">Empleado</label>
                    </div>
                  </div>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('nombres') ? ' has-error' : '' }}">
                  <label for="nombres">Nombres: *</label>
                  <input id="nombres" class="form-control" type="text" name="nombres" maxlength="50" value="{{ old('nombres', $usuario->nombres) }}" placeholder="Nombres" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                  <label for="apellidos">Apellidos: *</label>
                  <input id="apellidos" class="form-control" type="text" name="apellidos" maxlength="50" value="{{ old('apellidos', $usuario->apellidos) }}" placeholder="Apellidos" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('rut') ? ' has-error' : '' }}">
                  <label for="rut">RUT: *</label>
                  <input id="rut" class="form-control" type="text" name="rut" maxlength="11" pattern="^(\d{4,9}-[\dk])$" value="{{ old('rut', $usuario->rut) }}" placeholder="RUT" required>
                  <span class="help-block">Ejemplo: 00000000-0</span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                  <label for="telefono">Teléfono:</label>
                  <input id="telefono" class="form-control" type="text" name="telefono" maxlength="20" value="{{ old('telefono', $usuario->telefono) }}" placeholder="Teléfono">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email">Email:</label>
                  <input id="email" class="form-control" type="email" name="email" maxlength="50" value="{{ old('email', $usuario->email) }}" placeholder="Email">
                </div>
              </div>
            </div>

            <section>
              <legend class="form-legend">Permisos</legend>
              <p>Los permisos básicos de los Roles no pueden ser removidos.</p>

              <div class="custom-control custom-checkbox text-center">
                <input id="modulo-all" class="custom-control-input" type="checkbox">
                <label class="custom-control-label" for="modulo-all" title="Marcar todos los permisos de todos los modulo">
                  Todos los permisos
                </label>
              </div>

              @foreach($modulos as $modulo)
                <div class="form-group border-bottom pb-2">
                  <label for="modulo-{{ $modulo->id }}">{{ $modulo->name() }}:</label>
                  <div class="custom-control custom-checkbox">
                    <input id="modulo-{{ $modulo->id }}" class="custom-control-input check-modulo" type="checkbox">
                    <label class="custom-control-label" for="modulo-{{ $modulo->id }}" title="Marcar todos los permisos del modulo">
                      Todos
                    </label>
                  </div>

                  <div class="row">
                    @foreach($modulo->permissions as $permission)
                      <div class="col-md-4">
                        <div class="custom-control custom-checkbox">
                          <input id="permission-{{ $permission->id }}" class="custom-control-input" data-modulo="modulo-{{ $modulo->id }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}"{{ $usuario->permissions->contains($permission->id) ? ' checked' : '' }}>
                          <label class="custom-control-label" for="permission-{{ $permission->id }}" title="{{ $permission->description }}">
                            {{ $permission->display_name ?? 'N/A' }}
                          </label>
                          @if($permission->description)
                            </br>
                            <small class="text-muted">({{ $permission->description }})</small>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </section>

            @if(count($errors) > 0)
              <div class="alert alert-danger alert-important">
                <ul class="m-0">
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="text-right">
              <a class="btn btn-default btn-sm" href="{{ route('admin.usuario.show', ['usuario' => $usuario->id]) }}"><i class="fa fa-reply"></i> Atras</a>
              <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-send"></i> Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    const ROLES = @json($roles);
    const OLD_PERMISSIONS = @json($usuario->permissions->pluck('id'));

    $(document).ready( function () {
      $('input[name="role"]').change(rolesPermissions);
      $('input[name="role"]').change();

      $('#modulo-all').change(checkAll);
      $('.check-modulo').change(checkModulo);
    });

    function rolesPermissions(uncheckAll = true) {
      let name = $('input[name="role"]:checked').val();
      let role = ROLES.find(role => (role.name === name));

      if(uncheckAll){
        $.each($('input[id^="permission-"]'), function (k, v) {
          let permission = +$(v).val();
          let exist = OLD_PERMISSIONS.includes(permission);

          $(v).prop({'checked': exist, 'disabled': false});
        });

        $('#modulo-all').prop('checked', false).trigger('change');
      }

      $.each(role.permissions, function (k, v){
        $('#permission-'+v.id).prop({'checked': true, 'disabled': true});
      });
    }

    function checkAll() {
      let isChecked = $(this).is(':checked');
      
      $(`.check-modulo`)
        .prop('checked', isChecked)
        .trigger('change');

      if(!isChecked){
        rolesPermissions(false);
      }
    }

    function checkModulo() {
      let isChecked = $(this).is(':checked');
      let modulo = $(this).attr('id');
      
      $(`.custom-control-input[data-modulo="${modulo}"]`).prop('checked', isChecked);

      if(!isChecked){
        rolesPermissions(false);
      }
    }
  </script>
@endsection
