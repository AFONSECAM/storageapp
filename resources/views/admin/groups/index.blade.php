@extends('layout')

@section('content')
<h3>Gestión de Grupos</h3>

<button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#groupModal">Nuevo grupo</button>

<table id="groupTable" class="table table-bordered">
  <thead>
    <tr><th>Nombre</th><th>Cuota (bytes)</th><th>Acciones</th></tr>
  </thead>
  <tbody>
    @foreach($groups as $g)
      <tr>
        <td>{{ $g->name }}</td>
        <td>{{ $g->quota_limit ?? '-' }}</td>
        <td>
          <button class="btn btn-sm btn-warning btn-edit-group"
            data-id="{{ $g->id }}"
            data-name="{{ $g->name }}"
            data-quota="{{ $g->quota_limit }}"
            data-bs-toggle="modal"
            data-bs-target="#groupModal">
            Editar
          </button>

          <button class="btn btn-sm btn-danger btn-delete-group" data-id="{{ $g->id }}">
            Eliminar
          </button>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{ $groups->links() }}

<!-- Modal para Crear/Editar Grupo -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Grupo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="groupForm" data-method="POST">
        <div class="modal-body">
          @csrf
          <div class="mb-3">
            <label>Nombre</label>
            <input name="name" class="form-control" required>
          </div>
          <x-quota-input name="quota_limit" label="Cuota (MB)" />
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
