/**
 * ==========================================================================
 * NETPOS - TOAST.JS
 * Sistema de alertas/toasts flutuantes no canto inferior direito
 * Exposição da função global: showToast(type, message, title, duration)
 * ==========================================================================
 */

(function () {
  'use strict';

  // Obter ou criar o contentor global de toasts
  function getToastContainer() {
    var container = document.querySelector('.toast-container');
    if (!container) {
      container = document.createElement('div');
      container.className = 'toast-container';
      document.body.appendChild(container);
    }
    return container;
  }

  // Títulos e ícones padrão por tipo de alerta
  var TYPE_CONFIG = {
    success: {
      defaultTitle: 'Sucesso',
      iconClass: 'fa-solid fa-circle-check',
      itemClass: 'toast-success'
    },
    error: {
      defaultTitle: 'Erro',
      iconClass: 'fa-solid fa-circle-xmark',
      itemClass: 'toast-danger'
    },
    danger: {
      defaultTitle: 'Erro',
      iconClass: 'fa-solid fa-circle-xmark',
      itemClass: 'toast-danger'
    },
    warning: {
      defaultTitle: 'Aviso',
      iconClass: 'fa-solid fa-triangle-exclamation',
      itemClass: 'toast-warning'
    },
    info: {
      defaultTitle: 'Informação',
      iconClass: 'fa-solid fa-circle-info',
      itemClass: 'toast-info'
    }
  };

  /**
   * Exibe um alerta flutuante empilhado no canto inferior direito
   * @param {string} type - 'success', 'error', 'danger', 'warning', 'info'
   * @param {string} message - Mensagem detalhada a apresentar
   * @param {string} [title] - Título opcional
   * @param {number} [duration=4500] - Tempo em milissegundos antes de desaparecer
   */
  window.showToast = function (type, message, title, duration) {
    var config = TYPE_CONFIG[type] || TYPE_CONFIG.info;
    var finalTitle = title || config.defaultTitle;
    var finalDuration = typeof duration === 'number' ? duration : 4500;

    var container = getToastContainer();

    // Criação do elemento do toast
    var toast = document.createElement('div');
    toast.className = 'toast-item ' + config.itemClass;

    toast.innerHTML = [
      '<div class="toast-icon">',
      '  <i class="' + config.iconClass + '"></i>',
      '</div>',
      '<div class="toast-content">',
      '  <div class="toast-title">' + escapeHtml(finalTitle) + '</div>',
      '  <div class="toast-message">' + escapeHtml(message) + '</div>',
      '</div>',
      '<button type="button" class="toast-close-btn" aria-label="Fechar">&times;</button>'
    ].join('');

    // Inserção no contentor
    container.appendChild(toast);

    // Animação de entrada
    requestAnimationFrame(function () {
      toast.classList.add('toast-show');
    });

    // Função de fecho com animação
    function dismiss() {
      toast.classList.remove('toast-show');
      toast.classList.add('toast-hide');
      setTimeout(function () {
        if (toast.parentElement) {
          toast.parentElement.removeChild(toast);
        }
      }, 300);
    }

    // Botão de fechar manual
    var closeBtn = toast.querySelector('.toast-close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dismiss();
      });
    }

    // Auto-desaparecer após a duração definida
    if (finalDuration > 0) {
      setTimeout(dismiss, finalDuration);
    }
  };

  // Função auxiliar para evitar injeção de HTML acidental
  function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
  }
})();
