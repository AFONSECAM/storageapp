/**
 * SafeStorage - upload.js
 * Manejador de subida de archivos con barra de progreso y validaciones visuales
 */

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('uploadForm');
  if (!form) return;

  const fileInput = form.querySelector('input[type="file"]');
  const progressBarContainer = document.createElement('div');
  const progressBar = document.createElement('div');
  const statusText = document.createElement('p');

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
      alert('Selecciona un archivo primero');
      return;
    }

    // Deshabilitar el botón durante la subida
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

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
      submitBtn.disabled = false;

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
          msg = data.error || msg;
        } catch (err) {}
        progressBar.classList.add('bg-danger');
        statusText.className = 'text-danger';
        statusText.innerText = msg;
      }
    };

    // 🎯 Manejo de errores de red
    xhr.onerror = () => {
      progressBar.classList.add('bg-danger');
      statusText.className = 'text-danger';
      statusText.innerText = 'Error de conexión con el servidor';
      submitBtn.disabled = false;
    };

    // Enviar el archivo
    xhr.send(formData);
  });
});
