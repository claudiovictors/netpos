/**
 * ============================================================================
 * NETPOS - JS/POS/KEYPAD.JS
 * Controlador reutilizável de teclado numérico táctil para modais do POS:
 * Quantidade, Preço, Desconto, PIN de Gerente e Pagamentos.
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  var Keypad = {
    /**
     * Vincula um teclado a um mostrador ou campo de entrada
     * @param {HTMLElement|string} keypadContainer
     * @param {HTMLElement|string} targetInput
     * @param {Object} [options]
     */
    bind: function (keypadContainer, targetInput, options) {
      var container = typeof keypadContainer === 'string' ? document.querySelector(keypadContainer) : keypadContainer;
      var target = typeof targetInput === 'string' ? document.querySelector(targetInput) : targetInput;
      if (!container || !target) return;

      var opts = options || {};
      var isInput = target.tagName === 'INPUT';

      function getValue() {
        return isInput ? target.value : target.textContent;
      }

      function setValue(val) {
        if (isInput) {
          target.value = val;
          target.dispatchEvent(new Event('input', { bubbles: true }));
        } else {
          target.textContent = val;
        }
      }

      container.addEventListener('click', function (e) {
        var btn = e.target.closest('.pos-keypad-btn, .pos-stepper-btn');
        if (!btn) return;
        e.preventDefault();

        var current = getValue();

        if (btn.classList.contains('btn-clear')) {
          setValue(opts.defaultValue || '0');
          return;
        }

        if (btn.classList.contains('btn-backspace')) {
          if (current.length > 1) {
            setValue(current.slice(0, -1));
          } else {
            setValue(opts.defaultValue || '0');
          }
          return;
        }

        if (btn.id === 'btn-qty-minus') {
          var valMinus = Math.max(1, (parseFloat(current) || 1) - 1);
          setValue(String(valMinus));
          return;
        }

        if (btn.id === 'btn-qty-plus') {
          var valPlus = (parseFloat(current) || 0) + 1;
          setValue(String(valPlus));
          return;
        }

        var char = btn.textContent.trim();
        if (char === ',') {
          if (!current.includes(',')) {
            setValue(current + ',');
          }
          return;
        }

        // Dígitos 0-9
        if (/^\d$/.test(char)) {
          if (current === '0' || current === '') {
            setValue(char);
          } else {
            setValue(current + char);
          }
        }
      });
    }
  };

  window.PosKeypad = Keypad;
})(window, document);
