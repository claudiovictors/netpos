/**
 * ============================================================================
 * NETPOS - ERROR.JS
 * Script vanilla isolado para páginas de erro do sistema
 * Não possui dependências externas, executa de forma rápida e segura.
 * ============================================================================
 */

(function () {
  'use strict';

  /* ===== 1. INICIALIZAÇÃO AO CARREGAR O DOM ===== */
  document.addEventListener('DOMContentLoaded', function () {
    initBackButtons();
    initReloadButtons();
    initRetryCountdown();
  });

  /* ===== 2. BOTÕES "VOLTAR ATRÁS" ([data-action="back"]) ===== */
  function initBackButtons() {
    document.addEventListener('click', function (event) {
      var btn = event.target.closest('[data-action="back"]');
      if (!btn) return;

      event.preventDefault();

      var fallbackUrl = btn.getAttribute('data-fallback-url') || btn.getAttribute('href') || '/';

      // Se houver histórico anterior no mesmo separador, recua; caso contrário, vai para o fallback
      if (window.history && window.history.length > 1) {
        window.history.back();
      } else {
        window.location.href = fallbackUrl;
      }
    });
  }

  /* ===== 3. BOTÕES "TENTAR NOVAMENTE" ([data-action="reload"]) ===== */
  function initReloadButtons() {
    document.addEventListener('click', function (event) {
      var btn = event.target.closest('[data-action="reload"]');
      if (!btn) return;

      // Se o botão estiver desativado (ex.: durante contagem do 429), não faz nada
      if (btn.disabled || btn.hasAttribute('disabled')) {
        event.preventDefault();
        return;
      }

      event.preventDefault();
      window.location.reload();
    });
  }

  /* ===== 4. CONTAGEM DECRESCENTE PARA RATE LIMITING (ERRO 429) ===== */
  function initRetryCountdown() {
    var countdownEl = document.querySelector('[data-retry-after]');
    if (!countdownEl) return;

    var seconds = parseInt(countdownEl.getAttribute('data-retry-after'), 10);
    if (isNaN(seconds) || seconds <= 0) return;

    // Procura o botão de ação associado
    var actionBtn = countdownEl.tagName === 'BUTTON'
      ? countdownEl
      : countdownEl.querySelector('button') || document.querySelector('[data-action="reload"]');

    if (actionBtn) {
      actionBtn.disabled = true;
      updateButtonText(actionBtn, seconds);
    }

    var intervalId = setInterval(function () {
      seconds--;

      // Atualiza o texto do botão ou de qualquer span de contagem
      var numberDisplay = document.querySelector('[data-countdown-display]');
      if (numberDisplay) {
        numberDisplay.textContent = seconds;
      }

      if (actionBtn) {
        updateButtonText(actionBtn, seconds);
      }

      // Ao terminar o tempo, reativa o botão
      if (seconds <= 0) {
        clearInterval(intervalId);
        if (actionBtn) {
          actionBtn.disabled = false;
          actionBtn.innerHTML = '<i class="fa-solid fa-rotate-right" aria-hidden="true"></i> Tentar novamente';
        }
      }
    }, 1000);
  }

  function updateButtonText(btn, remainingSeconds) {
    btn.innerHTML = '<i class="fa-solid fa-rotate-right" aria-hidden="true"></i> Tentar novamente em ' + remainingSeconds + 's';
  }
})();
