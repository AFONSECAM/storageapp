@props(['name' => 'quota_limit', 'label' => 'Cuota (MB)', 'value' => null])

@php
    $globalQuota = \App\Models\Setting::get('global_quota', 10485760);
    $maxMB = round($globalQuota / (1024 * 1024), 1);
@endphp

<div class="mb-3">
    <label>{{ $label }}</label>
    <input name="{{ $name }}_mb" type="number" class="form-control quota-mb-input" step="0.1" min="0" max="{{ $maxMB }}" data-target="{{ $name }}">
    <input name="{{ $name }}" type="hidden" class="quota-bytes-input">
    <small class="text-muted">Máximo: {{ $maxMB }} MB (vacío para jeraquía)</small>
</div>

@once
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.quota-mb-input').forEach(mbInput => {
        const target = mbInput.dataset.target;
        const bytesInput = document.querySelector(`[name="${target}"]`);
        
        mbInput.addEventListener('input', function() {
            if (this.value === '' || this.value === null) {
                bytesInput.value = '';
            } else {
                const mb = parseFloat(this.value) || 0;
                bytesInput.value = Math.round(mb * 1024 * 1024);
            }
        });
    });
    
    // Manejar edición
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-edit-user') || e.target.classList.contains('btn-edit-group')) {
            const quotaBytes = e.target.dataset.quota;
            const mbInput = document.querySelector('.quota-mb-input');
            const bytesInput = document.querySelector('.quota-bytes-input');
            
            setTimeout(() => {
                if (quotaBytes && quotaBytes !== '' && quotaBytes !== 'null') {
                    const quotaMB = Math.round((quotaBytes / (1024 * 1024)) * 10) / 10;
                    mbInput.value = quotaMB;
                    bytesInput.value = quotaBytes;
                } else {
                    mbInput.value = '';
                    bytesInput.value = '';
                }
            }, 100);
        }
    });
});
</script>
@endonce