class AdminPanelManager {
  constructor() {
    this.csrf = document.querySelector('meta[name="csrf-token"]').content;
    this.init();
  }

  init() {
    this.initUsers();
    this.initGroups();
    this.initSettings();
    this.initModals();
  }

  // === UTILIDADES ===
  async makeRequest(url, options = {}) {
    const defaultOptions = {
      headers: {
        "X-CSRF-TOKEN": this.csrf,
        "X-Requested-With": "XMLHttpRequest"
      }
    };

    try {
      const response = await fetch(url, { ...defaultOptions, ...options });
      
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }
      
      return await response.json();
    } catch (error) {
      console.error('Request failed:', error);
      throw error;
    }
  }

  showAlert(message, type = "success") {
    const icon = type === "danger" ? "error" : type;
    
    Swal.fire({
      icon: icon,
      title: type === "danger" ? "Error" : "Éxito",
      text: message,
      timer: 3000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    });
  }

  // === USUARIOS ===
  initUsers() {
    const userTable = document.querySelector("#userTable");
    const userForm = document.querySelector("#userForm");

    if (userTable) {
      userTable.addEventListener("click", (e) => this.handleUserTableClick(e));
    }

    if (userForm) {
      userForm.addEventListener("submit", (e) => this.handleUserSubmit(e));
    }
  }

  async handleUserTableClick(e) {
    if (!e.target.classList.contains("btn-delete-user")) return;

    const id = e.target.dataset.id;
    
    const result = await Swal.fire({
      title: '¿Eliminar usuario?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    });
    
    if (!result.isConfirmed) return;

    try {
      const formData = new FormData();
      formData.append('_method', 'DELETE');

      await this.makeRequest(`/admin/users/${id}`, {
        method: "POST",
        body: formData
      });

      e.target.closest("tr").remove();
      this.showAlert("Usuario eliminado correctamente");
    } catch (error) {
      this.showAlert("Error al eliminar usuario", "danger");
    }
  }

  async handleUserSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const method = form.dataset.method || "POST";
    const id = form.dataset.id || "";

    let url = "/admin/users";
    if (method === "PUT") {
      url = `/admin/users/${id}`;
      formData.append('_method', 'PUT');
    }

    try {
      const result = await this.makeRequest(url, {
        method: "POST",
        body: formData
      });

      this.showAlert(result.message || "Usuario guardado correctamente");
      this.closeModal('userModal');
      
      if (method === "POST") {
        this.addUserToTable(result.user);
      } else {
        this.updateUserInTable(id, result.user);
      }
      
      form.reset();
    } catch (error) {
      this.showAlert("Error al guardar usuario", "danger");
    }
  }

  // === GRUPOS ===
  initGroups() {
    const groupTable = document.querySelector("#groupTable");
    const groupForm = document.querySelector("#groupForm");

    if (groupTable) {
      groupTable.addEventListener("click", (e) => this.handleGroupTableClick(e));
    }

    if (groupForm) {
      groupForm.addEventListener("submit", (e) => this.handleGroupSubmit(e));
    }
  }

  async handleGroupTableClick(e) {
    if (!e.target.classList.contains("btn-delete-group")) return;

    const id = e.target.dataset.id;
    
    const result = await Swal.fire({
      title: '¿Eliminar grupo?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    });
    
    if (!result.isConfirmed) return;

    try {
      const formData = new FormData();
      formData.append('_method', 'DELETE');

      await this.makeRequest(`/admin/groups/${id}`, {
        method: "POST",
        body: formData
      });

      e.target.closest("tr").remove();
      this.showAlert("Grupo eliminado correctamente");
    } catch (error) {
      this.showAlert("Error al eliminar grupo", "danger");
    }
  }

  async handleGroupSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const method = form.dataset.method || "POST";
    const id = form.dataset.id || "";

    let url = "/admin/groups";
    if (method === "PUT") {
      url = `/admin/groups/${id}`;
      formData.append('_method', 'PUT');
    }

    try {
      const result = await this.makeRequest(url, {
        method: "POST",
        body: formData
      });

      this.showAlert(result.message || "Grupo guardado correctamente");
      this.closeModal('groupModal');
      
      if (method === "POST") {
        this.addGroupToTable(result.group);
      } else {
        this.updateGroupInTable(id, result.group);
      }
      
      form.reset();
    } catch (error) {
      this.showAlert("Error al guardar grupo", "danger");
    }
  }

  // === CONFIGURACIÓN ===
  initSettings() {
    const settingsForm = document.querySelector("#settingsForm");
    
    if (settingsForm) {
      settingsForm.addEventListener("submit", (e) => this.handleSettingsSubmit(e));
    }
  }

  async handleSettingsSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);

    try {
      const result = await this.makeRequest("/admin/settings", {
        method: "POST",
        body: formData
      });

      this.showAlert(result.message || "Configuración actualizada correctamente");
    } catch (error) {
      this.showAlert("Error al guardar configuración", "danger");
    }
  }

  // === MODALES ===
  initModals() {
    this.initUserModal();
    this.initGroupModal();
  }

  initUserModal() {
    const userModal = document.getElementById("userModal");
    if (!userModal) return;

    userModal.addEventListener("show.bs.modal", (e) => {
      const btn = e.relatedTarget;
      const form = document.getElementById("userForm");
      
      if (!btn.classList.contains("btn-edit-user")) {
        this.resetForm(form, "POST");
        return;
      }

      this.populateUserForm(form, btn.dataset);
    });
  }

  initGroupModal() {
    const groupModal = document.getElementById("groupModal");
    if (!groupModal) return;

    groupModal.addEventListener("show.bs.modal", (e) => {
      const btn = e.relatedTarget;
      const form = document.getElementById("groupForm");
      
      if (!btn.classList.contains("btn-edit-group")) {
        this.resetForm(form, "POST");
        return;
      }

      this.populateGroupForm(form, btn.dataset);
    });
  }

  // === HELPERS ===
  resetForm(form, method) {
    form.reset();
    form.dataset.method = method;
    delete form.dataset.id;
  }

  populateUserForm(form, data) {
    form.dataset.method = "PUT";
    form.dataset.id = data.id;
    form.name.value = data.name;
    form.email.value = data.email;
    form.role.value = data.role;
    form.group_id.value = data.group || "";
    form.quota_limit.value = data.quota || "";
  }

  populateGroupForm(form, data) {
    form.dataset.method = "PUT";
    form.dataset.id = data.id;
    form.name.value = data.name;
    form.quota_limit.value = data.quota || "";
  }

  closeModal(modalId) {
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
    modal?.hide();
  }

  // === ACTUALIZACIÓN DE TABLAS ===
  addUserToTable(user) {
    const tbody = document.querySelector('#userTable tbody');
    const row = this.createUserRow(user);
    tbody.appendChild(row);
  }

  updateUserInTable(id, user) {
    const row = document.querySelector(`[data-id="${id}"]`).closest('tr');
    const newRow = this.createUserRow(user);
    row.replaceWith(newRow);
  }

  createUserRow(user) {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${user.name}</td>
      <td>${user.email}</td>
      <td>${user.role}</td>
      <td>${user.group?.name || '-'}</td>
      <td>${user.quota_limit || '-'}</td>
      <td>
        <button class="btn btn-sm btn-warning btn-edit-user"
          data-id="${user.id}"
          data-name="${user.name}"
          data-email="${user.email}"
          data-role="${user.role}"
          data-group="${user.group_id || ''}"
          data-quota="${user.quota_limit || ''}"
          data-bs-toggle="modal"
          data-bs-target="#userModal">
          Editar
        </button>
        <button class="btn btn-sm btn-danger btn-delete-user" data-id="${user.id}">
          Eliminar
        </button>
      </td>
    `;
    return row;
  }

  addGroupToTable(group) {
    const tbody = document.querySelector('#groupTable tbody');
    const row = this.createGroupRow(group);
    tbody.appendChild(row);
  }

  updateGroupInTable(id, group) {
    const row = document.querySelector(`[data-id="${id}"]`).closest('tr');
    const newRow = this.createGroupRow(group);
    row.replaceWith(newRow);
  }

  createGroupRow(group) {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${group.name}</td>
      <td>${group.quota_limit || '-'}</td>
      <td>
        <button class="btn btn-sm btn-warning btn-edit-group"
          data-id="${group.id}"
          data-name="${group.name}"
          data-quota="${group.quota_limit || ''}"
          data-bs-toggle="modal"
          data-bs-target="#groupModal">
          Editar
        </button>
        <button class="btn btn-sm btn-danger btn-delete-group" data-id="${group.id}">
          Eliminar
        </button>
      </td>
    `;
    return row;
  }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
  new AdminPanelManager();
});