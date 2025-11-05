@extends('layout')

@section('content')
<h3>Configuración del sistema</h3>
<form id="settingsForm" method="POST" action="{{ route('admin.settings.update') }}">@csrf
  <div class="mb-3">
    <label>Cuota global (MB)</label>
    <input type="number" name="global_quota_mb" class="form-control" step="0.1" min="0" value="{{ $globalQuota ? round($globalQuota / (1024*1024), 1) : '' }}">
    <input type="hidden" name="global_quota" value="{{ $globalQuota }}">
  </div>
  <div class="mb-3">
    <label>Extensiones prohibidas (separadas por coma)</label>
    <input type="text" name="banned_extensions" class="form-control" value="{{ $banned }}">
  </div>
  <button class="btn btn-primary">Guardar cambios</button>
</form>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mbInput = document.querySelector('[name="global_quota_mb"]');
    const bytesInput = document.querySelector('[name="global_quota"]');
    
    if (mbInput && bytesInput) {
        mbInput.addEventListener('input', function() {
            if (this.value === '' || this.value === null) {
                bytesInput.value = '';
            } else {
                const mb = parseFloat(this.value) || 0;
                bytesInput.value = Math.round(mb * 1024 * 1024);
            }
        });
    }
});
</script>