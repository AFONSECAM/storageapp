@extends('layout')

@section('content')
<h3>Gestión de Usuarios</h3>

<!-- Botón para abrir el modal -->
<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#userModal">Nuevo usuario</button>

<table id="userTable" class="table table-bordered">
  <thead>
    <tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Grupo</th><th>Cuota (bytes)</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    @foreach($users as $u)
      <tr>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td>{{ $u->group?->name ?? '-' }}</td>
        <td>{{ $u->quota_limit ?? '-' }}</td>
        <td>
          <button class="btn btn-sm btn-warning btn-edit-user"
            data-id="{{ $u->id }}"
            data-name="{{ $u->name }}"
            data-email="{{ $u->email }}"
            data-role="{{ $u->role }}"
            data-group="{{ $u->group_id }}"
            data-quota="{{ $u->quota_limit }}"
            data-bs-toggle="modal"
            data-bs-target="#userModal">
            Editar
          </button>

          <button class="btn btn-sm btn-danger btn-delete-user" data-id="{{ $u->id }}">
            Eliminar
          </button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{ $users->links() }}

<!-- Modal para Crear/Editar Usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="userForm" data-method="POST">
        <div class="modal-body">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Nombre</label>
              <input name="name" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label>Email</label>
              <input name="email" type="email" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Contraseña (solo al crear o cambiar)</label>
              <input name="password" type="password" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label>Rol</label>
              <select name="role" class="form-select">
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Grupo</label>
              <select name="group_id" class="form-select">
                <option value="">-- Ninguno --</option>
                @foreach($groups as $g)
                  <option value="{{ $g->id }}">{{ $g->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">              
              <x-quota-input name="quota_limit" label="Cuota personalizada (MB)" />
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
