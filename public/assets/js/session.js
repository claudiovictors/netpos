/**
 * ============================================================================
 * NETPOS - JS/POS/SESSION.JS
 * Gestão de Sessões de Caixa e Segurança do Posto de Venda:
 * - Abertura de Turno obrigatória e bloqueante (não permite vender sem turno aberto)
 * - Fecho de Turno e Conferência de Dinheiro Contado vs. Esperado (com sangrias)
 * - Bloqueio de Ecrã táctil com PIN de 4 dígitos
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  var Session = {
    activeSession: null,
    lockPin: '',

    init: function () {
      var self = this;

      // 1. Verificar estado da sessão no arranque
      this.checkSessionStatus();

      // 2. Vincular Abertura de Turno
      var btnOpenSession = document.getElementById('btn-confirm-open-session');
      if (btnOpenSession) {
        btnOpenSession.addEventListener('click', function (e) {
          e.preventDefault();
          self.handleOpenSession();
        });
      }

      // 3. Vincular Fecho de Dia / Turno
      var btnCloseDayTrigger = document.getElementById('btn-close-day');
      if (btnCloseDayTrigger) {
        btnCloseDayTrigger.addEventListener('click', function (e) {
          e.preventDefault();
          self.prepareCloseDayModal();
          if (window.openModal) {
            window.openModal('modal-close-day');
          }
        });
      }

      var btnConfirmCloseDay = document.getElementById('btn-confirm-close-day');
      if (btnConfirmCloseDay) {
        btnConfirmCloseDay.addEventListener('click', function (e) {
          e.preventDefault();
          self.handleCloseDay();
        });
      }

      // Conferência de dinheiro em tempo real no fecho
      var countedCashInput = document.getElementById('close-day-counted-cash');
      if (countedCashInput) {
        countedCashInput.addEventListener('input', function () {
          self.updateCloseDayDifference();
        });
      }

      // 4. Bloqueio de Ecrã
      var btnLockTrigger = document.getElementById('btn-lock-screen');
      if (btnLockTrigger) {
        btnLockTrigger.addEventListener('click', function (e) {
          e.preventDefault();
          self.lock();
        });
      }

      // Teclado numérico do Ecrã de Bloqueio
      var lockKeypad = document.getElementById('lock-pin-keypad');
      if (lockKeypad) {
        lockKeypad.addEventListener('click', function (e) {
          var btn = e.target.closest('.pos-keypad-btn');
          if (!btn) return;
          e.preventDefault();

          if (btn.classList.contains('btn-clear')) {
            self.lockPin = '';
            self.updatePinDots();
            return;
          }

          if (btn.classList.contains('btn-backspace')) {
            self.lockPin = self.lockPin.slice(0, -1);
            self.updatePinDots();
            return;
          }

          var digit = btn.textContent.trim();
          if (/^\d$/.test(digit) && self.lockPin.length < 4) {
            self.lockPin += digit;
            self.updatePinDots();

            if (self.lockPin.length === 4) {
              self.verifyUnlock();
            }
          }
        });
      }
    },

    checkSessionStatus: function () {
      var stored = localStorage.getItem('netpos_active_session');
      if (stored) {
        try {
          this.activeSession = JSON.parse(stored);
        } catch (e) {
          this.activeSession = null;
        }
      }

      var statusShiftEl = document.getElementById('pos-status-session-time');

      if (!this.activeSession) {
        if (statusShiftEl) statusShiftEl.textContent = 'Turno Fechado';
        setTimeout(function () {
          if (window.openModal) {
            window.openModal('modal-open-session');
          }
        }, 200);
      } else {
        var openDate = new Date(this.activeSession.opened_at);
        var timeStr = String(openDate.getHours()).padStart(2, '0') + ':' + String(openDate.getMinutes()).padStart(2, '0');
        if (statusShiftEl) statusShiftEl.textContent = 'Aberto às ' + timeStr;
      }
    },

    handleOpenSession: function () {
      var initialCashInput = document.getElementById('initial-cash');
      var btnConfirm = document.getElementById('btn-confirm-open-session');
      if (!initialCashInput || !btnConfirm) return;

      var val = parseFloat(initialCashInput.value);
      if (isNaN(val) || val < 0) {
        var help = initialCashInput.closest('.form-group').querySelector('.help-block');
        if (help) help.textContent = 'O fundo de maneio deve ser igual ou superior a 0,00 Kz.';
        initialCashInput.classList.add('has-error');
        return;
      }

      var originalHtml = btnConfirm.innerHTML;
      btnConfirm.disabled = true;
      btnConfirm.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A processar...';

      var self = this;
      window.PosApi.openSession(val).then(function (res) {
        btnConfirm.disabled = false;
        btnConfirm.innerHTML = originalHtml;
        self.activeSession = res.session;

        if (window.closeModal) {
          window.closeModal('modal-open-session');
        }

        self.checkSessionStatus();
        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Turno de caixa aberto com sucesso!');
        }
      });
    },

    prepareCloseDayModal: function () {
      var format = window.PosFormat || {
        currency: function (v) { return (v || 0).toFixed(2) + ' Kz'; }
      };

      var initial = this.activeSession ? this.activeSession.initial_cash : 10000;
      var salesCash = 122000; // Mock de vendas em dinheiro
      var refunds = 0;
      var sangrias = 0;
      var expected = initial + salesCash - refunds - sangrias;

      var initialEl = document.getElementById('close-day-initial');
      var salesEl = document.getElementById('close-day-sales');
      var expectedEl = document.getElementById('close-day-expected');
      var countedInput = document.getElementById('close-day-counted-cash');

      if (initialEl) initialEl.textContent = format.currency(initial);
      if (salesEl) salesEl.textContent = format.currency(salesCash);
      if (expectedEl) expectedEl.textContent = format.currency(expected);
      if (countedInput) {
        countedInput.value = expected;
        this.updateCloseDayDifference();
      }
    },

    updateCloseDayDifference: function () {
      var format = window.PosFormat || {
        currency: function (v) { return (v || 0).toFixed(2) + ' Kz'; }
      };

      var expectedEl = document.getElementById('close-day-expected');
      var countedInput = document.getElementById('close-day-counted-cash');
      var diffEl = document.getElementById('close-day-difference');

      var expected = window.PosFormat ? window.PosFormat.parseCurrency(expectedEl.textContent) : 132000;
      var counted = parseFloat(countedInput.value) || 0;
      var diff = counted - expected;

      if (diffEl) {
        diffEl.textContent = format.currency(diff);
        if (diff === 0) {
          diffEl.style.color = 'var(--pos-success)';
        } else {
          diffEl.style.color = 'var(--pos-danger)';
        }
      }
    },

    handleCloseDay: function () {
      var btn = document.getElementById('btn-confirm-close-day');
      if (!btn) return;

      var originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A processar...';

      var self = this;
      window.PosApi.closeSession({}).then(function () {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        self.activeSession = null;

        if (window.closeModal) {
          window.closeModal('modal-close-day');
        }

        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Turno de caixa encerrado com sucesso!');
        }

        self.checkSessionStatus();
      });
    },

    lock: function () {
      this.lockPin = '';
      this.updatePinDots();
      var lockModal = document.getElementById('modal-lock-screen');
      if (lockModal) {
        lockModal.classList.add('is-open');
      }
    },

    updatePinDots: function () {
      for (var i = 1; i <= 4; i++) {
        var dot = document.getElementById('lock-pin-dot-' + i);
        if (dot) {
          if (i <= this.lockPin.length) {
            dot.classList.add('is-filled');
          } else {
            dot.classList.remove('is-filled');
          }
        }
      }
    },

    verifyUnlock: function () {
      var self = this;
      setTimeout(function () {
        if (self.lockPin === '1234') {
          var lockModal = document.getElementById('modal-lock-screen');
          if (lockModal) {
            lockModal.classList.remove('is-open');
          }
          if (typeof window.showToast === 'function') {
            window.showToast('success', 'Ecrã desbloqueado com sucesso!');
          }
        } else {
          if (typeof window.showToast === 'function') {
            window.showToast('danger', 'PIN de desbloqueio incorreto! (PIN mock: 1234)');
          }
          self.lockPin = '';
          self.updatePinDots();
        }
      }, 150);
    }
  };

  window.PosSession = Session;
})(window, document);
