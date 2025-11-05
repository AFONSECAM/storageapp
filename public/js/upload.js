document.addEventListener('DOMContentLoaded', async () => {
  const form = document.getElementById('uploadForm');
  if (!form) return;

  const fileInput = form.querySelector('input[type="file"]');
  const progressBarContainer = document.createElement('div');
  const progressBar = document.createElement('div');
  const statusText = document.createElement('p');
  
  // Obtener configuración del backend
  let bannedExtensions = ['exe', 'bat', 'js', 'php', 'sh']; // fallback
  try {
    const configRes = await fetch('/config');
    const config = await configRes.json();
    bannedExtensions = config.banned_extensions;
  } catch (err) {
    console.warn('No se pudo cargar configuración, usando valores por defecto');
  }

  // 🎨 Estilos básicos para la barra de progreso
  progressBarContainer.classList.add('progress', 'my-3');
  progressBar.classList.add('progress-bar', 'progress-bar-striped', 'progress-bar-animated');
  progressBar.style.width = '0%';
  progressBarContainer.appendChild(progressBar);
  form.appendChild(progressBarContainer);
  form.appendChild(statusText);

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const file = fileInput.files[0];
    if (!file) {
      Swal.fire({
        icon: 'warning',
        title: 'Archivo requerido',
        text: 'Selecciona un archivo primero'
      });
      return;
    }

    // Validar extensión antes de subir
    const fileName = file.name.toLowerCase();
    const ext = fileName.split('.').pop();
    
    if (bannedExtensions.includes(ext)) {
      Swal.fire({
        icon: 'error',
        title: 'Tipo de archivo no permitido',
        text: `El tipo de archivo '.${ext}' no está permitido`
      });
      statusText.className = 'text-danger';
      statusText.innerText = `Tipo de archivo .${ext} no permitido`;
      return;
    }

    // Deshabilitar el botón durante la subida
    const submitBtn = form.querySelector('button');
    if (submitBtn) submitBtn.disabled = true;

    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();

    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    // 🎯 Evento de progreso
    xhr.upload.addEventListener('progress', (e) => {
      if (e.lengthComputable) {
        const percent = Math.round((e.loaded / e.total) * 100);
        progressBar.style.width = `${percent}%`;
        progressBar.innerText = `${percent}%`;
      }
    });

    // 🎯 Cuando la carga termina
    xhr.onload = () => {
      if (submitBtn) submitBtn.disabled = false;

      if (xhr.status === 200) {
        const data = JSON.parse(xhr.responseText);
        progressBar.classList.remove('bg-danger');
        progressBar.classList.add('bg-success');
        progressBar.innerText = '100%';
        statusText.className = 'text-success';
        statusText.innerText = data.message || 'Archivo subido correctamente';
        setTimeout(() => window.location.reload(), 1500);
      } else {
        let msg = 'Error al subir el archivo';
        try {
          const data = JSON.parse(xhr.responseText);
          msg = data.error || data.message || msg;
          console.log('Error response:', data);
        } catch (err) {
          console.error('Error parsing response:', err, xhr.responseText);
        }
        progressBar.classList.add('bg-danger');
        progressBar.style.width = '100%';
        progressBar.innerText = 'Error';
        statusText.className = 'text-danger';
        statusText.innerText = msg;
        
        // Mostrar alerta con SweetAlert
        Swal.fire({
          icon: 'error',
          title: 'Error al subir archivo',
          text: msg
        });
      }
    };

    // 🎯 Manejo de errores de red
    xhr.onerror = () => {
      progressBar.classList.add('bg-danger');
      statusText.className = 'text-danger';
      statusText.innerText = 'Error de conexión con el servidor';
      if (submitBtn) submitBtn.disabled = false;
    };

    // Enviar el archivo
    xhr.send(formData);
  });
});
