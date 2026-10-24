/**
 * Gestão de clientes: validação no cliente + submissão AJAX + paginação e pesquisa.
 *
 * - Valida os campos no browser (espelha CustomerRequest).
 * - Submete via fetch() sem recarregar a página.
 * - Mapeia erros 422 do servidor de volta para cada campo.
 * - Depende de window.showToast(type, message) e window.closeModal(id).
 * - Suporta paginação, pesquisa e eliminação individual com CSRF.
 *
 * @file assets/admin/js/customer.js
 */
(() => {
  "use strict";

  /** Classe CSS aplicada a campos inválidos. */
  const ERROR_CLASS = "is-invalid";

  /** Classe CSS aplicada ao `.form-group` de um campo inválido. */
  const GROUP_ERROR_CLASS = "has-error";

  /** Selector dos formulários tratados por este script. */
  const FORM_SELECTOR = "form[data-customer-form]";

  /** Classes do ícone de spinner no botão durante o carregamento. */
  const SPINNER_CLASSES = "fa-solid fa-spinner fa-spin btn-icon";

  /**
   * Estado actual da listagem de clientes.
   *
   * @type {{ page: number, perPage: number, search: string }}
   */
  const listState = {
    page: 1,
    perPage: 20,
    search: "",
  };

  /**
   * Regras de validação de clientes.
   *
   * @type {Object.<string, function(string): (string|null)>}
   */
  const RULES = {
    name: (v) => {
      if (!v.trim()) return "O nome do cliente é obrigatório.";
      if (v.length > 255) return "O nome não pode exceder 255 caracteres.";
      return null;
    },
    email: (v) => {
      if (v.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim())) {
        return "O formato do email é inválido.";
      }
      return null;
    },
  };

  /**
   * Obtém o token CSRF da meta tag ou de um input hidden.
   *
   * @returns {string}
   */
  function getCsrfToken() {
    return (
      document.querySelector('meta[name="csrf-token"]')?.content ??
      document.querySelector('input[name="_token"]')?.value ??
      ""
    );
  }

  /**
   * Mostra uma mensagem de erro por baixo de um campo.
   *
   * @param {HTMLElement} field
   * @param {string} message
   * @returns {void}
   */
  function setError(field, message) {
    const group = field.closest(".form-group");
    if (!group) return;

    group.classList.add(GROUP_ERROR_CLASS);
    field.classList.add(ERROR_CLASS);

    const help = group.querySelector(".help-block");
    if (help) help.textContent = message;
  }

  /**
   * Remove o estado de erro de um único campo.
   *
   * @param {HTMLElement} field
   * @returns {void}
   */
  function clearError(field) {
    const group = field.closest(".form-group");
    if (!group) return;

    group.classList.remove(GROUP_ERROR_CLASS);
    field.classList.remove(ERROR_CLASS);

    const help = group.querySelector(".help-block");
    if (help) help.textContent = "";
  }

  /**
   * Remove o estado de erro de todos os campos de um formulário.
   *
   * @param {HTMLFormElement} form
   * @returns {void}
   */
  function clearAllErrors(form) {
    form.querySelectorAll("[name]").forEach(clearError);
  }

  /**
   * Alterna o estado de carregamento (spinner) de um botão.
   *
   * @param {HTMLButtonElement} button
   * @param {boolean} loading
   * @returns {void}
   */
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

  /**
   * Valida um único campo contra a sua regra.
   *
   * @param {HTMLElement} field
   * @returns {boolean}
   */
  function validateField(field) {
    const rule = RULES[field.name];
    if (!rule) return true;

    const message = rule(field.value ?? "");
    if (message) {
      setError(field, message);
      return false;
    }

    clearError(field);
    return true;
  }

  /**
   * Valida todos os campos de um formulário e foca o primeiro inválido.
   *
   * @param {HTMLFormElement} form
   * @returns {boolean}
   */
  function validateForm(form) {
    let firstInvalid = null;

    form.querySelectorAll("[name]").forEach((field) => {
      if (!validateField(field) && !firstInvalid) firstInvalid = field;
    });

    if (firstInvalid) firstInvalid.focus();
    return firstInvalid === null;
  }

  /**
   * Aplica os erros de validação do servidor (HTTP 422) ao formulário.
   *
   * @param {HTMLFormElement} form
   * @param {Object.<string, string|string[]>} errors
   * @returns {void}
   */
  function applyServerErrors(form, errors) {
    Object.entries(errors).forEach(([name, messages]) => {
      const field = form.querySelector(`[name="${name}"]`);
      if (!field) return;
      setError(field, Array.isArray(messages) ? messages[0] : messages);
    });
  }

  /**
   * Valida e submete um formulário via fetch() sem recarregar a página.
   *
   * @param {HTMLFormElement} form
   * @returns {Promise<void>}
   */
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
          "X-CSRF-TOKEN": getCsrfToken(),
        },
        body: new FormData(form),
      });

      const data = await response.json().catch(() => ({}));

      if (response.status === 422) {
        applyServerErrors(form, data.errors ?? {});
        window.showToast(
          "danger",
          data.message ?? "Existem erros no formulário.",
        );
        return;
      }

      if (!response.ok) {
        window.showToast(
          "danger",
          data.message ?? "Erro ao processar o pedido.",
        );
        return;
      }

      window.showToast(
        "success",
        data.message ?? "Operação concluída com sucesso.",
      );
      form.reset();
      clearAllErrors(form);
      if (modal) window.closeModal(modal.id);

      await loadCustomers();
    } catch {
      window.showToast("danger", "Falha de ligação ao servidor.");
    } finally {
      form.dataset.submitting = "false";
      setLoading(button, false);
    }
  }

  /**
   * Preenche o formulário de edição com os dados do cliente.
   *
   * @param {HTMLElement} trigger Botão com data-edit-customer.
   * @returns {void}
   */
  function fillEditForm(trigger) {
    const form = document.getElementById("form-edit-customer");
    if (!form) return;

    let customer;
    try {
      customer = JSON.parse(trigger.dataset.editCustomer);
    } catch {
      window.showToast(
        "danger",
        "Não foi possível carregar os dados do cliente.",
      );
      return;
    }

    clearAllErrors(form);

    Object.entries(customer).forEach(([name, value]) => {
      const field = form.querySelector(`[name="${name}"]`);
      if (field) field.value = value ?? "";
    });
  }

  /**
   * Prepara o modal de eliminação com o ID, nome e URL do cliente.
   *
   * @param {HTMLElement} trigger Botão com data-delete-id / data-delete-url.
   * @returns {void}
   */
  function prepareDeleteModal(trigger) {
    const btn = document.getElementById("btn-confirm-delete-customer");
    const nameEl = document.getElementById("delete-customer-name");

    if (!btn) return;

    btn.dataset.customerId = trigger.dataset.deleteId ?? "";
    btn.dataset.deleteUrl = trigger.dataset.deleteUrl ?? "";

    if (nameEl) {
      nameEl.textContent = trigger.dataset.deleteName ?? "este cliente";
    }
  }

  /**
   * Envia o pedido de eliminação ao servidor (CSRF no body e no header).
   *
   * @returns {Promise<void>}
   */
  async function confirmDelete() {
    const btn = document.getElementById("btn-confirm-delete-customer");
    if (!btn || btn.dataset.deleting === "true") return;

    const url = btn.dataset.deleteUrl;
    if (!url) {
      window.showToast("danger", "URL de eliminação inválida.");
      return;
    }

    const csrfToken = getCsrfToken();

    btn.dataset.deleting = "true";
    setLoading(btn, true);

    try {
      const body = new FormData();
      body.append("_token", csrfToken);

      const response = await fetch(url, {
        method: "POST",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": csrfToken,
        },
        body,
      });

      const data = await response.json().catch(() => ({}));

      if (!response.ok) {
        window.showToast(
          "danger",
          data.message ?? "Não foi possível eliminar o cliente.",
        );
        return;
      }

      window.closeModal("modal-delete-customer");
      window.showToast(
        "success",
        data.message ?? "Cliente eliminado com sucesso.",
      );
      await loadCustomers();
    } catch {
      window.showToast("danger", "Falha de ligação ao servidor.");
    } finally {
      btn.dataset.deleting = "false";
      setLoading(btn, false);
    }
  }

  /**
   * Constrói a query string a partir do estado actual da listagem.
   *
   * @returns {string}
   */
  function buildQueryString() {
    const params = new URLSearchParams();
    params.set("page", String(listState.page));
    params.set("per_page", String(listState.perPage));

    if (listState.search) params.set("search", listState.search);

    return params.toString();
  }

  /**
   * Carrega a listagem de clientes via AJAX.
   *
   * @returns {Promise<void>}
   * @throws {Error}
   */
  async function loadCustomers() {
    const url = `${window.location.pathname}?${buildQueryString()}`;

    const response = await fetch(url, {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    if (!response.ok) throw new Error("Falha ao carregar a lista de clientes.");

    const html = await response.text();
    const wrapper = document.getElementById("customers-table-wrapper");
    if (wrapper) {
      wrapper.innerHTML = html;
    }

    document
      .querySelectorAll("[data-select-all]")
      .forEach((box) => (box.checked = false));
    window.history.replaceState(null, "", url);
  }

  /**
   * Lê os valores do formulário de filtros e actualiza o estado interno.
   *
   * @returns {void}
   */
  function syncFiltersFromForm() {
    const form = document.getElementById("form-customer-filter");
    if (!form) return;

    listState.search =
      form.querySelector('[name="search"]')?.value?.trim() ?? "";
    listState.page = 1;
  }

  // --- Event listeners ---

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
    const editTrigger = event.target.closest("[data-edit-customer]");
    if (editTrigger) fillEditForm(editTrigger);

    const deleteTrigger = event.target.closest("[data-delete-id]");
    if (deleteTrigger) prepareDeleteModal(deleteTrigger);
  });

  document
    .getElementById("btn-confirm-delete-customer")
    ?.addEventListener("click", () => {
      confirmDelete();
    });

  document.addEventListener("click", (event) => {
    const link = event.target.closest("#pagination-links a[data-page]");
    if (!link) return;

    event.preventDefault();
    const page = parseInt(link.dataset.page, 10);
    if (Number.isNaN(page) || page < 1) return;

    listState.page = page;
    loadCustomers().catch(() => {
      window.showToast("danger", "Não foi possível carregar a página.");
    });
  });

  document.addEventListener("change", (event) => {
    if (event.target.id !== "per-page-select") return;

    listState.perPage = parseInt(event.target.value, 10) || 20;
    listState.page = 1;
    loadCustomers().catch(() => {
      window.showToast("danger", "Não foi possível actualizar a lista.");
    });
  });

  document
    .getElementById("form-customer-filter")
    ?.addEventListener("submit", (event) => {
      event.preventDefault();
      syncFiltersFromForm();
      loadCustomers()
        .then(() => window.showToast("info", "Filtros aplicados."))
        .catch(() =>
          window.showToast("danger", "Não foi possível aplicar os filtros."),
        );
    });

  document
    .querySelector('[data-action="reset-filter"]')
    ?.addEventListener("click", () => {
      const form = document.getElementById("form-customer-filter");
      if (form) form.reset();

      listState.search = "";
      listState.page = 1;

      loadCustomers()
        .then(() => window.showToast("info", "Filtros limpos."))
        .catch(() =>
          window.showToast("danger", "Não foi possível limpar os filtros."),
        );
    });

  document
    .querySelector('[data-action="refresh"]')
    ?.addEventListener("click", async function () {
      setLoading(this, true);
      try {
        await loadCustomers();
        window.showToast("info", "Lista de clientes sincronizada com sucesso.");
      } catch {
        window.showToast("danger", "Não foi possível actualizar a lista.");
      } finally {
        setLoading(this, false);
      }
    });
})();