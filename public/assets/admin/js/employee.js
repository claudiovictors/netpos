/**
 * Gestão de funcionários: validação no cliente + submissão AJAX + paginação e pesquisa.
 *
 * @file assets/admin/js/employee.js
 */
(() => {
  "use strict";

  const ERROR_CLASS = "is-invalid";
  const GROUP_ERROR_CLASS = "has-error";
  const FORM_SELECTOR = "form[data-employee-form]";
  const SPINNER_CLASSES = "fa-solid fa-spinner fa-spin btn-icon";

  /**
   * Estado atual da listagem de funcionários.
   * 
   * @type {{ page: number, perPage: number, search: string, status: string }}
   */
  const listState = {
    page: 1,
    perPage: 20,
    search: "",
    status: "",
  };

  /**
   * Regras de validação de funcionários.
   * 
   * @type {Object.<string, function(string, HTMLFormElement): (string|null)>}
   */
  const RULES = {
    name: (v) => {
      if (!v.trim()) return "O nome é obrigatório.";
      if (v.length > 255) return "O nome não pode exceder 255 caracteres.";
      return null;
    },
    email: (v) => {
      if (!v.trim()) return "O email é obrigatório.";
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) {
        return "O formato do email é inválido.";
      }
      return null;
    },
    password: (v, form) => {
      const isCreateForm = form?.id === "form-add-employee";
      if (isCreateForm && !v.trim()) {
        return "A palavra-passe é obrigatória.";
      }
      if (v.trim() && v.trim().length < 6) {
        return "A palavra-passe deve ter pelo menos 6 caracteres.";
      }
      return null;
    },
  };

  function setError(field, message) {
    const group = field.closest(".form-group");
    if (!group) return;

    group.classList.add(GROUP_ERROR_CLASS);
    field.classList.add(ERROR_CLASS);

    const help = group.querySelector(".help-block");
    if (help) help.textContent = message;
  }

  function clearError(field) {
    const group = field.closest(".form-group");
    if (!group) return;

    group.classList.remove(GROUP_ERROR_CLASS);
    field.classList.remove(ERROR_CLASS);

    const help = group.querySelector(".help-block");
    if (help) help.textContent = "";
  }

  function clearAllErrors(form) {
    form.querySelectorAll("[name]").forEach(clearError);
  }

  function setLoading(button, loading) {
    if (!button) return;
    const icon = button.querySelector(".btn-icon");

    if (loading) {
      button.disabled = true;
      if (icon) {
        button.dataset.originalIcon = icon.className;
        icon.className = SPINNER_CLASSES;
      }
    } else {
      button.disabled = false;
      if (icon && button.dataset.originalIcon) {
        icon.className = button.dataset.originalIcon;
      }
    }
  }

  function validateField(field) {
    const rule = RULES[field.name];
    if (!rule) return true;

    const message = rule(field.value ?? "", field.form);
    if (message) {
      setError(field, message);
      return false;
    }

    clearError(field);
    return true;
  }

  function validateForm(form) {
    let firstInvalid = null;

    form.querySelectorAll("[name]").forEach((field) => {
      if (!validateField(field) && !firstInvalid) firstInvalid = field;
    });

    if (firstInvalid) firstInvalid.focus();
    return firstInvalid === null;
  }

  function applyServerErrors(form, errors) {
    Object.entries(errors).forEach(([name, messages]) => {
      const field = form.querySelector(`[name="${name}"]`);
      if (!field) return;
      setError(field, Array.isArray(messages) ? messages[0] : messages);
    });
  }

  async function submitForm(form) {
    if (form.dataset.submitting === "true") return;

    const button = form.querySelector('button[type="submit"]');
    const modal = form.closest(".modal");

    if (!validateForm(form)) {
      window.showToast("warning", "Corrija os campos assinalados.");
      return;
    }

    form.dataset.submitting = "true";
    setLoading(button, true);

    try {
      const response = await fetch(form.action, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]')?.content ?? "",
        },
        body: new FormData(form),
      });

      const data = await response.json().catch(() => ({}));

      if (response.status === 422) {
        applyServerErrors(form, data.errors ?? {});
        window.showToast("danger", data.message ?? "Existem erros no formulário.");
        return;
      }

      if (!response.ok) {
        window.showToast("danger", data.message ?? "Erro ao processar o pedido.");
        return;
      }

      window.showToast("success", data.message ?? "Operação concluída com sucesso.");
      form.reset();
      clearAllErrors(form);
      if (modal) window.closeModal(modal.id);

      await loadEmployees();
    } catch {
      window.showToast("danger", "Falha de ligação ao servidor.");
    } finally {
      form.dataset.submitting = "false";
      setLoading(button, false);
    }
  }

  function fillEditForm(trigger) {
    const form = document.getElementById("form-edit-employee");
    if (!form) return;

    let employee;
    try {
      employee = JSON.parse(trigger.dataset.editEmployee);
    } catch {
      window.showToast("danger", "Não foi possível carregar os dados do funcionário.");
      return;
    }

    clearAllErrors(form);

    Object.entries(employee).forEach(([name, value]) => {
      if (name === "password") return; // Não preenche a palavra-passe no edit
      const field = form.querySelector(`[name="${name}"]`);
      if (field) field.value = value ?? "";
    });
  }

  function buildQueryString() {
    const params = new URLSearchParams();
    params.set("page", String(listState.page));
    params.set("per_page", String(listState.perPage));

    if (listState.search) params.set("search", listState.search);
    if (listState.status) params.set("status", listState.status);

    return params.toString();
  }

  async function loadEmployees() {
    const url = `${window.location.pathname}?${buildQueryString()}`;

    const response = await fetch(url, {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    if (!response.ok) throw new Error("Falha ao carregar a lista de funcionários.");

    const html = await response.text();
    const wrapper = document.getElementById("employees-table-wrapper");
    if (wrapper) {
      wrapper.innerHTML = html;
    }

    document.querySelectorAll("[data-select-all]").forEach((box) => (box.checked = false));
    window.history.replaceState(null, "", url);
  }

  function syncFiltersFromForm() {
    const form = document.getElementById("form-employee-filter");
    if (!form) return;

    listState.search = form.querySelector('[name="search"]')?.value?.trim() ?? "";
    listState.status = form.querySelector('[name="status"]')?.value ?? "";
    listState.page = 1;
  }

  document.addEventListener("submit", (event) => {
    const form = event.target.closest(FORM_SELECTOR);
    if (!form) return;

    event.preventDefault();
    submitForm(form);
  });

  document.addEventListener("input", (event) => {
    const field = event.target;
    if (field.name && field.closest(FORM_SELECTOR)) clearError(field);
  });

  document.addEventListener("focusout", (event) => {
    const field = event.target;
    if (field.name && field.closest?.(FORM_SELECTOR)) validateField(field);
  });

  document.addEventListener("click", (event) => {
    const trigger = event.target.closest("[data-edit-employee]");
    if (trigger) fillEditForm(trigger);
  });

  document.addEventListener("click", (event) => {
    const link = event.target.closest("#pagination-links a[data-page]");
    if (!link) return;

    event.preventDefault();
    const page = parseInt(link.dataset.page, 10);
    if (Number.isNaN(page) || page < 1) return;

    listState.page = page;
    loadEmployees().catch(() => {
      window.showToast("danger", "Não foi possível carregar a página.");
    });
  });

  document.addEventListener("change", (event) => {
    if (event.target.id !== "per-page-select") return;

    listState.perPage = parseInt(event.target.value, 10) || 20;
    listState.page = 1;
    loadEmployees().catch(() => {
      window.showToast("danger", "Não foi possível actualizar a lista.");
    });
  });

  document
    .getElementById("form-employee-filter")
    ?.addEventListener("submit", (event) => {
      event.preventDefault();
      syncFiltersFromForm();
      loadEmployees()
        .then(() => window.showToast("info", "Filtros aplicados."))
        .catch(() => window.showToast("danger", "Não foi possível aplicar os filtros."));
    });

  document
    .querySelector('[data-action="reset-filter"]')
    ?.addEventListener("click", () => {
      const form = document.getElementById("form-employee-filter");
      if (form) form.reset();

      listState.search = "";
      listState.status = "";
      listState.page = 1;

      loadEmployees()
        .then(() => window.showToast("info", "Filtros limpos."))
        .catch(() => window.showToast("danger", "Não foi possível limpar os filtros."));
    });

  document
    .querySelector('[data-action="refresh"]')
    ?.addEventListener("click", async function () {
      setLoading(this, true);
      try {
        await loadEmployees();
        window.showToast("info", "Lista de funcionários sincronizada com sucesso.");
      } catch {
        window.showToast("danger", "Não foi possível actualizar a lista.");
      } finally {
        setLoading(this, false);
      }
    });
})();