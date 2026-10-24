/**
 * ============================================================================
 * NETPOS - JS/POS/MODAL.JS
 * Gestão universal de janelas modais.
 *
 * Regras:
 *  - Só existe UM modal aberto de cada vez.
 *  - Abrir um modal que já está aberto é ignorado (evita duplicados).
 *  - Abertura via atributo [data-modal-open="id"] ou window.openModal('id').
 *  - Fecho via [data-modal-close], tecla ESC ou clique no fundo
 *    (exceto modais bloqueantes).
 *  - Focus trap com TAB e restauro do foco ao fechar.
 *
 * Eventos disparados no modal: 'pos:modal-opened' e 'pos:modal-closed'.
 * ============================================================================
 */
(function (window, document) {
  'use strict';

  // Evita carregar o script duas vezes
  if (window.__posModalInit) return;
  window.__posModalInit = true;

  /* ===== CONFIGURAÇÃO ===== */
  var DEBUG = false; // true = mostra na consola quem abre o modal-pin
  var BLOCKING_MODALS = ['modal-open-session', 'modal-lock-screen'];
  var FOCUSABLE = 'a[href], button:not([disabled]), textarea:not([disabled]), ' +
                  'input:not([disabled]):not([type="hidden"]), select:not([disabled]), ' +
                  '[tabindex]:not([tabindex="-1"])';

  /* ===== ESTADO ===== */
  var activeModal = null;
  var lastTriggerElement = null;
  var backdrop = null;

  /* ===== AUXILIARES ===== */
  function getBackdrop() {
    if (!backdrop) {
      backdrop = document.getElementById('pos-modal-backdrop') ||
                 document.querySelector('.pos-modal-backdrop');
    }
    return backdrop;
  }

  function getOpenModals() {
    return document.querySelectorAll('.pos-modal.is-open, .pos-lock-overlay.is-open');
  }

  /* ===== ABRIR ===== */
  function openModal(modalId, triggerEl) {
    if (!modalId) return;
    modalId = String(modalId).replace(/^#/, '');

    var modal = document.getElementById(modalId);
    if (!modal) {
      console.warn('[NetPos Modal] Modal não encontrado:', modalId);
      return;
    }

    // Diagnóstico: mostra quem abriu o PIN
    if (DEBUG && modalId === 'modal-pin') {
      console.trace('[NetPos Modal] modal-pin aberto por:');
    }

    // Já está aberto: ignora
    if (modal.classList.contains('is-open')) return;

    // Fecha todos os outros (modo silencioso, sem mexer no foco)
    getOpenModals().forEach(function (m) {
      if (m !== modal) closeModal(m, false, true);
    });

    lastTriggerElement = triggerEl || document.activeElement;
    activeModal = modal;

    getBackdrop().classList.add('is-open');
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');

    // Foco no primeiro campo
    setTimeout(function () {
      var first = modal.querySelector(FOCUSABLE);
      if (first) {
        first.focus();
        if (first.select && first.type !== 'checkbox') first.select();
      }
    }, 50);

    modal.dispatchEvent(new CustomEvent('pos:modal-opened', {
      bubbles: true,
      detail: { modalId: modalId }
    }));
  }

  /* ===== FECHAR ===== */
  function closeModal(target, restoreFocus, silent) {
    var modal = typeof target === 'string' ? document.getElementById(target) : target;
    if (!modal || !modal.classList.contains('is-open')) return;

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');

    var remaining = getOpenModals();
    if (remaining.length === 0) {
      getBackdrop().classList.remove('is-open');
      activeModal = null;
    } else {
      activeModal = remaining[remaining.length - 1];
    }

    if (!silent) {
      if (restoreFocus !== false && lastTriggerElement &&
          typeof lastTriggerElement.focus === 'function') {
        try { lastTriggerElement.focus(); } catch (e) {}
      }
      // Sem modais abertos: devolve o foco ao leitor de código de barras
      if (!activeModal) {
        var barcode = document.getElementById('barcode-input');
        if (barcode) setTimeout(function () { barcode.focus(); }, 50);
      }
    }

    modal.dispatchEvent(new CustomEvent('pos:modal-closed', {
      bubbles: true,
      detail: { modalId: modal.id }
    }));
  }

  /* ===== FOCUS TRAP ===== */
  function trapFocus(e) {
    if (!activeModal || e.key !== 'Tab') return;
    var items = activeModal.querySelectorAll(FOCUSABLE);
    if (!items.length) return;

    var first = items[0];
    var last = items[items.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  /* ===== EVENTOS (um único listener de clique) ===== */
  document.addEventListener('click', function (e) {
    // 1. Abrir
    var openBtn = e.target.closest('[data-modal-open]');
    if (openBtn) {
      e.preventDefault();
      openModal(openBtn.getAttribute('data-modal-open'), openBtn);
      return;
    }

    // 2. Fechar pelo botão
    var closeBtn = e.target.closest('[data-modal-close]');
    if (closeBtn) {
      e.preventDefault();
      var modal = closeBtn.closest('.pos-modal');
      if (modal) closeModal(modal);
      return;
    }

    // 3. Clique no fundo
    if (e.target.classList && e.target.classList.contains('pos-modal-backdrop')) {
      if (activeModal && BLOCKING_MODALS.indexOf(activeModal.id) === -1) {
        closeModal(activeModal);
      }
    }
  });

  document.addEventListener('keydown', function (e) {
    if (!activeModal) return;

    if (e.key === 'Escape' && BLOCKING_MODALS.indexOf(activeModal.id) === -1) {
      e.preventDefault();
      closeModal(activeModal);
    }
    trapFocus(e);
  });

  /* ===== API GLOBAL ===== */
  window.openModal = openModal;
  window.closeModal = closeModal;
  window.PosModal = {
    open: openModal,
    close: closeModal,
    getActive: function () { return activeModal; }
  };
})(window, document);