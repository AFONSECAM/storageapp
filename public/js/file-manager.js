async function confirmDelete(fileName, event) {
  const form = event.target.closest('form');
  event.preventDefault();
  
  const result = await Swal.fire({
    title: '¿Eliminar archivo?',
    text: `¿Estás seguro de eliminar "${fileName}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  });
  
  if (!result.isConfirmed) return false;
  
  try {
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    const response = await fetch(form.action, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });
    
    const data = await response.json();
    
    if (response.ok) {
      // Mostrar alerta de éxito
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: data.message || 'Archivo eliminado correctamente',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
      });
      
      // Eliminar fila de la tabla
      form.closest('tr').remove();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: data.message || 'Error al eliminar archivo'
      });
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Error de conexión'
    });
  }
  
  return false;
}