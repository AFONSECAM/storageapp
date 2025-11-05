/**
 * SafeStorage - admin.js
 * Módulo JS para CRUD dinámico de Usuarios y Grupos mediante Fetch API
 * Requiere que las vistas Blade tengan botones con data-action y data-id
 */

document.addEventListener("DOMContentLoaded", () => {

  // --- ⚙️ FUNCIONES UTILITARIAS ---
  const csrf = document.querySelector('meta[name="csrf-token"]').content;

  const fetchJSON = async (url, options = {}) => {
    const res = await fetch(url, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": csrf,
        ...(options.headers || {}),
      },
      ...options,
    });
    return res.json();
  };

  const showAlert = (msg, type = "success") => {
    const alert = document.createElement("div");
    alert.className = `alert alert-${type}`;
    alert.innerText = msg;
    document.body.prepend(alert);
    setTimeout(() => alert.remove(), 3000);
  };

  // ============================================================
  // 🧑‍💼 SECCIÓN: USUARIOS
  // ============================================================
  const userTable = document.querySelector("#userTable");
  if (userTable) {
    // Eliminar usuario
    userTable.addEventListener("click", async (e) => {
      if (e.target.classList.contains("btn-delete-user")) {
        const id = e.target.dataset.id;
        if (!confirm("¿Eliminar este usuario?")) return;

        const res = await fetch(`/api/admin/users/${id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": csrf },
        });

        if (res.ok) {
          e.target.closest("tr").remove();
          showAlert("Usuario eliminado correctamente");
        } else {
          showAlert("Error al eliminar el usuario", "danger");
        }
      }
    });
  }

  // Crear usuario (desde modal o formulario)
  const userForm = document.querySelector("#userForm");
  if (userForm) {
    userForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(userForm).entries());
      const method = userForm.dataset.method || "POST";
      const id = userForm.dataset.id || "";

      const url = method === "POST" ? "/api/admin/users" : `/api/admin/users/${id}`;
      const res = await fetchJSON(url, {
        method,
        body: JSON.stringify(data),
        headers: { "Content-Type": "application/json" },
      });

      if (res.message || res.success) {
        showAlert("Usuario guardado correctamente");
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert(res.error || "Error al guardar", "danger");
      }
    });
  }

  // ============================================================
  // 👥 SECCIÓN: GRUPOS
  // ============================================================
  const groupTable = document.querySelector("#groupTable");
  if (groupTable) {
    // Eliminar grupo
    groupTable.addEventListener("click", async (e) => {
      if (e.target.classList.contains("btn-delete-group")) {
        const id = e.target.dataset.id;
        if (!confirm("¿Eliminar este grupo?")) return;

        const res = await fetch(`/api/admin/groups/${id}`, {
          method: "DELETE",
          headers: { "X-CSRF-TOKEN": csrf },
        });

        if (res.ok) {
          e.target.closest("tr").remove();
          showAlert("Grupo eliminado correctamente");
        } else {
          showAlert("Error al eliminar grupo", "danger");
        }
      }
    });
  }

  // Crear / Editar grupo
  const groupForm = document.querySelector("#groupForm");
  if (groupForm) {
    groupForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(groupForm).entries());
      const method = groupForm.dataset.method || "POST";
      const id = groupForm.dataset.id || "";
      const url = method === "POST" ? "/api/admin/groups" : `/api/admin/groups/${id}`;

      const res = await fetchJSON(url, {
        method,
        body: JSON.stringify(data),
        headers: { "Content-Type": "application/json" },
      });

      if (res.message || res.success) {
        showAlert("Grupo guardado correctamente");
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert(res.error || "Error al guardar", "danger");
      }
    });
  }

  // ============================================================
  // ⚙️ CONFIGURACIÓN GLOBAL (Settings)
  // ============================================================
  const settingsForm = document.querySelector("#settingsForm");
  if (settingsForm) {
    settingsForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(settingsForm).entries());

      const res = await fetchJSON("/api/admin/settings", {
        method: "POST",
        body: JSON.stringify(data),
        headers: { "Content-Type": "application/json" },
      });

      if (res.success || res.message) {
        showAlert("Configuración actualizada correctamente");
      } else {
        showAlert(res.error || "Error al guardar configuración", "danger");
      }
    });
  }

  // ============================================================
  // ✨ CARGAR DATOS EN MODALES DE EDICIÓN
  // ============================================================

  // --- Usuarios ---
  const userModal = document.getElementById("userModal");
  if (userModal) {
    userModal.addEventListener("show.bs.modal", (e) => {
      const btn = e.relatedTarget;
      const form = document.getElementById("userForm");
      if (!btn.classList.contains("btn-edit-user")) {
        form.reset();
        form.dataset.method = "POST";
        delete form.dataset.id;
        return;
      }

      // Cargar datos del usuario
      form.dataset.method = "PUT";
      form.dataset.id = btn.dataset.id;
      form.name.value = btn.dataset.name;
      form.email.value = btn.dataset.email;
      form.role.value = btn.dataset.role;
      form.group_id.value = btn.dataset.group || "";
      form.quota_limit.value = btn.dataset.quota || "";
    });
  }

  // --- Grupos ---
  const groupModal = document.getElementById("groupModal");
  if (groupModal) {
    groupModal.addEventListener("show.bs.modal", (e) => {
      const btn = e.relatedTarget;
      const form = document.getElementById("groupForm");
      if (!btn.classList.contains("btn-edit-group")) {
        form.reset();
        form.dataset.method = "POST";
        delete form.dataset.id;
        return;
      }

      // Cargar datos del grupo
      form.dataset.method = "PUT";
      form.dataset.id = btn.dataset.id;
      form.name.value = btn.dataset.name;
      form.quota_limit.value = btn.dataset.quota || "";
    });
  }
});
