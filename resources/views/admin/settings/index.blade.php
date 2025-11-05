@extends('layout')

@section('content')
<h3>Configuración del sistema</h3>
<form method="POST" action="{{ route('admin.settings.update') }}">@csrf
  <div class="mb-3">
    <label>Cuota global (bytes)</label>
    <input type="number" name="global_quota" class="form-control" value="{{ $globalQuota }}">
  </div>
  <div class="mb-3">
    <label>Extensiones prohibidas (separadas por coma)</label>
    <input type="text" name="banned_extensions" class="form-control" value="{{ $banned }}">
  </div>
  <button class="btn btn-primary">Guardar cambios</button>
</form>
@endsection
