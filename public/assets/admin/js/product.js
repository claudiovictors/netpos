/**
 * Gestão de produtos: validação no cliente + submissão AJAX + paginação.
 *
 * - Valida os campos no browser (espelha ProductRequest.php).
 * - Submete via fetch() sem recarregar a página.
 * - Mapeia erros 422 do servidor de volta para cada campo.
 * - Utiliza a mesma classe CSS de erro que o @error_class() do Slenix.
 * - Depende de window.showToast(type, message) definido em toast.js.
 * - Suporta paginação, pesquisa, filtros e alteração de "registos por página" via AJAX.
 * - Suporta eliminação individual com confirmação em modal.
 *
 * @file assets/admin/js/product.js
 */
(() => {
  "use strict";

  /** Classe CSS aplicada a campos inválidos. */
  const ERROR_CLASS = "is-invalid";

  /** Classe CSS aplicada ao `.form-group` de um campo inválido. */
  const GROUP_ERROR_CLASS = "has-error";

  /** Selector dos formulários tratados por este script. */
  const FORM_SELECTOR = "form[data-product-form]";

  /** Classes do ícone de spinner no botão durante o carregamento. */
  const SPINNER_CLASSES = "fa-solid fa-spinner fa-spin btn-icon";

  /**
   * Estado actual da listagem (pesquisa + filtros + paginação).
   *
   * @type {{
   *   page: number,
   *   perPage: number,
   *   search: string,
   *   categoryId: string,
   *   status: string,
   *   stockLevel: string
   * }}
   */
  const listState = {
    page: 1,
    perPage: 20,
    search: "",
    categoryId: "",
    status: "",
    stockLevel: "",
  };

  /**
   * Regras de validação sincronizadas com o backend.
   *
   * @type {Object.<string, function(string): (string|null)>}
   */
  const RULES = {
    name: (v) => {
      if (!v.trim()) return "O nome do produto é obrigatório.";
      if (v.trim().length < 2)
        return "O nome deve ter pelo menos 2 caracteres.";
      if (v.length > 255) return "O nome não pode exceder 255 caracteres.";
      return null;
    },
    code: (v) => {
      if (!v.trim()) return "O código interno é obrigatório.";
      if (v.length > 50) return "O código não pode exceder 50 caracteres.";
      return null;
    },
    barcode: (v) =>
      v.trim() && !/^\d{8,14}$/.test(v.trim())
        ? "Código de barras inválido (8 a 14 dígitos)."
        : null,
    category_id: (v) => (!v ? "Seleccione uma categoria." : null),
    cost_price: (v) =>
      v !== "" && (Number.isNaN(Number(v)) || Number(v) < 0)
        ? "Preço de custo inválido."
        : null,
    sale_price: (v) => {
      if (v === "") return "O preço de venda é obrigatório.";
      if (Number.isNaN(Number(v)) || Number(v) < 0)
        return "Preço de venda inválido.";
      return null;
    },
    stock: (v) =>
      v !== "" && !/^\d+$/.test(v)
        ? "O stock deve ser um número inteiro ≥ 0."
        : null,
    min_stock: (v) =>
      v !== "" && !/^\d+$/.test(v)
        ? "O stock mínimo deve ser um número inteiro ≥ 0."
        : null,
    status: (v) =>
      !["active", "inactive"].includes(v) ? "Estado inválido." : null,
    description: (v) =>
      v.length > 1000 ? "A descrição não pode exceder 1000 caracteres." : null,
  };

  /**
   * Mostra uma mensagem de erro por baixo de um campo.
   *
   * @param {HTMLElement} field Input, select ou textarea.
   * @param {string} message Mensagem de erro a apresentar.
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
   * @param {HTMLElement} field Input, select ou textarea.
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
   * @param {HTMLFormElement} form Formulário alvo.
   * @returns {void}
   */
  function clearAllErrors(form) {
    form.querySelectorAll("[name]").forEach(clearError);
  }

  /**
   * Alterna o estado de carregamento (spinner) de um botão.
   *
   * @param {HTMLButtonElement} button Botão alvo.
   * @param {boolean} loading Indica se o botão está em carregamento.
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
   * @param {HTMLElement} field Input, select ou textarea.
   * @returns {boolean} true se for válido.
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
   * @param {HTMLFormElement} form Formulário alvo.
   * @returns {boolean} true se todos os campos forem válidos.
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
   * @param {HTMLFormElement} form Formulário alvo.
   * @param {Object.<string, string|string[]>} errors Erros retornados pelo servidor.
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
   * @param {HTMLFormElement} form Formulário alvo.
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
          "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]')?.content ?? "",
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

      await loadProducts();
    } catch {
      window.showToast("danger", "Falha de ligação ao servidor.");
    } finally {
      form.dataset.submitting = "false";
      setLoading(button, false);
    }
  }

  /**
   * Preenche o formulário de edição com os dados do produto guardados no botão.
   *
   * @param {HTMLElement} trigger Botão com o atributo data-edit-product.
   * @returns {void}
   */
  function fillEditForm(trigger) {
    const form = document.getElementById("form-edit-product");
    if (!form) return;

    let product;
    try {
      product = JSON.parse(trigger.dataset.editProduct);
    } catch {
      window.showToast(
        "danger",
        "Não foi possível carregar os dados do produto.",
      );
      return;
    }

    clearAllErrors(form);

    Object.entries(product).forEach(([name, value]) => {
      const field = form.querySelector(`[name="${name}"]`);
      if (field) field.value = value ?? "";
    });
  }

  /**
   * Prepara o modal de eliminação com o ID, nome e URL do produto.
   *
   * @param {HTMLElement} trigger Botão com data-delete-id / data-delete-url.
   * @returns {void}
   */
  function prepareDeleteModal(trigger) {
    const btn = document.getElementById("btn-confirm-delete");
    const nameEl = document.getElementById("delete-product-name");

    if (!btn) return;

    btn.dataset.productId = trigger.dataset.deleteId ?? "";
    btn.dataset.deleteUrl = trigger.dataset.deleteUrl ?? "";

    if (nameEl) {
      nameEl.textContent = trigger.dataset.deleteName ?? "este produto";
    }
  }

  /**
   * Envia o pedido de eliminação ao servidor e actualiza a listagem.
   *
   * @returns {Promise<void>}
   */
  async function confirmDelete() {
    const btn = document.getElementById("btn-confirm-delete");
    if (!btn || btn.dataset.deleting === "true") return;

    const url = btn.dataset.deleteUrl;
    if (!url) {
      window.showToast("danger", "URL de eliminação inválida.");
      return;
    }

    const csrfToken =
      document.querySelector('meta[name="csrf-token"]')?.content ??
      document.querySelector('input[name="_token"]')?.value ??
      "";

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
          data.message ?? "Não foi possível eliminar o produto.",
        );
        return;
      }

      window.closeModal("modal-delete-confirm");
      window.showToast(
        "success",
        data.message ?? "Produto eliminado com sucesso.",
      );
      await loadProducts();
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
   * @returns {string} Query string formatada.
   */
  function buildQueryString() {
    const params = new URLSearchParams();
    params.set("page", String(listState.page));
    params.set("per_page", String(listState.perPage));

    if (listState.search) params.set("search", listState.search);
    if (listState.categoryId) params.set("category_id", listState.categoryId);
    if (listState.status) params.set("status", listState.status);
    if (listState.stockLevel) params.set("stock_level", listState.stockLevel);

    return params.toString();
  }

  /**
   * Carrega a listagem de produtos via AJAX.
   *
   * @returns {Promise<void>}
   * @throws {Error} Quando a página de listagem não pode ser carregada.
   */
  async function loadProducts() {
    const url = `${window.location.pathname}?${buildQueryString()}`;

    const response = await fetch(url, {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    if (!response.ok) throw new Error("Falha ao carregar a lista de produtos.");

    const html = await response.text();
    const wrapper = document.getElementById("products-table-wrapper");
    if (wrapper) {
      wrapper.innerHTML = html;
    }

    document
      .querySelectorAll("[data-select-all]")
      .forEach((box) => (box.checked = false));
    window.history.replaceState(null, "", url);
  }

  /**
   * Lê os valores actuais do formulário de filtros e actualiza o estado interno.
   *
   * @returns {void}
   */
  function syncFiltersFromForm() {
    const form = document.getElementById("form-product-filter");
    if (!form) return;

    listState.search =
      form.querySelector('[name="search"]')?.value?.trim() ?? "";
    listState.categoryId =
      form.querySelector('[name="category_id"]')?.value ?? "";
    listState.status = form.querySelector('[name="status"]')?.value ?? "";
    listState.stockLevel =
      form.querySelector('[name="stock_level"]')?.value ?? "";
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
    const editTrigger = event.target.closest("[data-edit-product]");
    if (editTrigger) fillEditForm(editTrigger);

    const deleteTrigger = event.target.closest("[data-delete-id]");
    if (deleteTrigger) prepareDeleteModal(deleteTrigger);
  });

  document
    .getElementById("btn-confirm-delete")
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
    loadProducts().catch(() => {
      window.showToast("danger", "Não foi possível carregar a página.");
    });
  });

  document.addEventListener("change", (event) => {
    if (event.target.id !== "per-page-select") return;

    listState.perPage = parseInt(event.target.value, 10) || 20;
    listState.page = 1;
    loadProducts().catch(() => {
      window.showToast("danger", "Não foi possível actualizar a lista.");
    });
  });

  document
    .getElementById("form-product-filter")
    ?.addEventListener("submit", (event) => {
      event.preventDefault();
      syncFiltersFromForm();
      loadProducts()
        .then(() => window.showToast("info", "Filtros aplicados."))
        .catch(() =>
          window.showToast("danger", "Não foi possível aplicar os filtros."),
        );
    });

  document
    .querySelector('[data-action="reset-filter"]')
    ?.addEventListener("click", () => {
      const form = document.getElementById("form-product-filter");
      if (form) form.reset();

      listState.search = "";
      listState.categoryId = "";
      listState.status = "";
      listState.stockLevel = "";
      listState.page = 1;

      loadProducts()
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
        await loadProducts();
        window.showToast(
          "info",
          "Catálogo de produtos sincronizado com sucesso.",
        );
      } catch {
        window.showToast("danger", "Não foi possível actualizar a lista.");
      } finally {
        setLoading(this, false);
      }
    });
})();
