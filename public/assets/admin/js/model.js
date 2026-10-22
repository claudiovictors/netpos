/**
 * ==========================================================================
 * NETPOS - MODEL.JS
 * Gestão universal de Janelas Modais e Menus Suspensos (Dropdowns)
 * - Abertura/fecho de modais via atributos data-modal-open e data-modal-close
 * - Fecho com tecla ESC e clique fora do conteúdo (no backdrop)
 * - Controlo de menus dropdown ("Acções", "Exportar", perfil do utilizador)
 * ==========================================================================
 */

(function () {
  'use strict';

  /* ===== 1. FUNÇÕES GLOBAIS DE MODAIS ===== */

  // Garante a existência do backdrop no DOM
  function getOrCreateBackdrop() {
    var backdrop = document.querySelector('.modal-backdrop');
    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.className = 'modal-backdrop';
      document.body.appendChild(backdrop);
    }
    return backdrop;
  }

  // Abrir Modal por ID
  window.openModal = function (modalId) {
    var modal = document.getElementById(modalId);
    if (!modal) {
      console.warn('Modal não encontrado com o ID:', modalId);
      return;
    }

    var backdrop = getOrCreateBackdrop();
    backdrop.classList.add('show');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';

    // Focar no primeiro campo de input disponível
    var firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
    if (firstInput) {
      setTimeout(function () {
        firstInput.focus();
      }, 100);
    }
  };

  // Fechar Modal por ID ou referência de elemento
  window.closeModal = function (target) {
    var modal = typeof target === 'string' ? document.getElementById(target) : target.closest('.modal');
    if (modal) {
      modal.classList.remove('show');
    }

    // Se nenhum modal estiver mais aberto, remove o backdrop e restaura o scroll
    var remainingModals = document.querySelectorAll('.modal.show');
    if (remainingModals.length === 0) {
      var backdrop = document.querySelector('.modal-backdrop');
      if (backdrop) {
        backdrop.classList.remove('show');
      }
      document.body.style.overflow = '';
    }
  };

  /* ===== 2. INICIALIZAÇÃO E DELEGAÇÃO DE EVENTOS ===== */
  document.addEventListener('DOMContentLoaded', function () {
    var backdrop = getOrCreateBackdrop();

    // Event Delegation para botões de abrir modal
    document.addEventListener('click', function (e) {
      var openBtn = e.target.closest('[data-modal-open]');
      if (openBtn) {
        e.preventDefault();
        var targetId = openBtn.getAttribute('data-modal-open');
        window.openModal(targetId);
        return;
      }

      // Botões de fechar modal (com data-modal-close ou data-dismiss="modal" ou classe .close)
      var closeBtn = e.target.closest('[data-modal-close], [data-dismiss="modal"], .modal .close');
      if (closeBtn) {
        e.preventDefault();
        var targetModal = closeBtn.closest('.modal');
        if (targetModal) {
          window.closeModal(targetModal);
        }
        return;
      }

      // Fechar modal ao clicar diretamente na área cinzenta externa do modal
      if (e.target.classList.contains('modal')) {
        window.closeModal(e.target);
        return;
      }

      // Fechar modal se clicar no backdrop
      if (e.target === backdrop) {
        var openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(function (m) {
          window.closeModal(m);
        });
      }
    });

    // Fechar modais abertos com a tecla ESC
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' || e.keyCode === 27) {
        var openModals = document.querySelectorAll('.modal.show');
        if (openModals.length > 0) {
          openModals.forEach(function (m) {
            window.closeModal(m);
          });
        }
        // Também fecha dropdowns abertos
        closeAllDropdowns();
      }
    });

    /* ===== 3. CONTROLO DE MENUS DROPDOWN (SUSPENSOS) ===== */
    function closeAllDropdowns(exceptElement) {
      var openDropdowns = document.querySelectorAll('.dropdown.open, .user-menu.open, [data-dropdown-container].open');
      openDropdowns.forEach(function (drop) {
        if (!exceptElement || !drop.contains(exceptElement)) {
          drop.classList.remove('open');
        }
      });
    }

    // Toggle de dropdowns via clique
    document.addEventListener('click', function (e) {
      var toggle = e.target.closest('[data-toggle="dropdown"], [data-dropdown-toggle], .dropdown-toggle');

      if (toggle) {
        e.preventDefault();
        e.stopPropagation();

        var container = toggle.closest('.dropdown, .user-menu, [data-dropdown-container]');
        if (container) {
          var isAlreadyOpen = container.classList.contains('open');
          closeAllDropdowns(container);
          if (!isAlreadyOpen) {
            container.classList.add('open');
          }
        }
        return;
      }

      // Ao clicar em qualquer outro sítio da página, fecha todos os dropdowns abertos
      closeAllDropdowns();
    });
  });
})();
