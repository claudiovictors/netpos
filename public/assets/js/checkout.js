/**
 * ============================================================================
 * NETPOS - JS/POS/CHECKOUT.JS
 * Gestão do ecrã de checkout e finalização de venda:
 * - Pagamento misto (Dinheiro, TPA, Multicaixa Express, Transferência Bancária)
 * - Campos de referência obrigatórios para pagamentos eletrónicos
 * - Botões rápidos de notas e cálculo de troco em tempo real
 * - Validação fiscal: total >= 50.000 Kz exige identificação de NIF do cliente
 * - Submissão assíncrona com botão com spinner e abertura do talão fiscal
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  var Checkout = {
    activeMethods: [], // [{ id: 'cash', name: 'Dinheiro', amount: 0, ref: '' }]
    targetTotal: 0,

    init: function () {
      var self = this;

      // Abrir checkout quando o utilizador clica em "Fechar Conta"
      var btnCheckout = document.getElementById('btn-checkout');
      if (btnCheckout) {
        btnCheckout.addEventListener('click', function (e) {
          e.preventDefault();
          self.open();
        });
      }

      // Vincular cliques nos meios de pagamento do modal
      document.addEventListener('click', function (e) {
        var tile = e.target.closest('.pos-method-tile');
        if (tile) {
          e.preventDefault();
          var methodId = tile.getAttribute('data-method');
          var methodName = tile.getAttribute('data-name');
          self.toggleMethod(methodId, methodName);
        }
      });

      // Vincular botões rápidos de dinheiro
      document.addEventListener('click', function (e) {
        var quickBtn = e.target.closest('.pos-quick-cash-btn');
        if (quickBtn) {
          e.preventDefault();
          var val = quickBtn.getAttribute('data-val');
          self.applyQuickValue(val);
        }
      });

      // Confirmar Pagamento com spinner
      var btnConfirm = document.getElementById('btn-confirm-payment');
      if (btnConfirm) {
        btnConfirm.addEventListener('click', function (e) {
          e.preventDefault();
          self.processPayment();
        });
      }
    },

    open: function () {
      var totals = window.PosCart ? window.PosCart.getTotals() : { total: 0 };
      if (totals.total <= 0) {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'Não é possível fechar conta de um carrinho vazio.');
        }
        return;
      }

      this.targetTotal = totals.total;

      // Por defeito, inicia com Dinheiro com o valor total exato
      this.activeMethods = [
        { id: 'cash', name: 'Dinheiro', amount: this.targetTotal, ref: '' }
      ];

      this.render();
      if (window.openModal) {
        window.openModal('modal-checkout');
      }
    },

    toggleMethod: function (id, name) {
      var index = this.activeMethods.findIndex(function (m) { return m.id === id; });
      if (index >= 0) {
        // Se for o único, não remove
        if (this.activeMethods.length > 1) {
          this.activeMethods.splice(index, 1);
        }
      } else {
        // Adiciona meio com o valor que ainda falta pagar
        var currentPaid = this.getTotalPaid();
        var remaining = Math.max(0, this.targetTotal - currentPaid);
        this.activeMethods.push({
          id: id,
          name: name,
          amount: remaining,
          ref: ''
        });
      }
      this.render();
    },

    applyQuickValue: function (val) {
      // Aplica ao método de dinheiro, ou ao primeiro método ativo
      var cashMethod = this.activeMethods.find(function (m) { return m.id === 'cash'; }) || this.activeMethods[0];
      if (!cashMethod) return;

      if (val === 'exact') {
        cashMethod.amount = this.targetTotal;
      } else {
        var numeric = parseFloat(val) || 0;
        cashMethod.amount = numeric;
      }
      this.render();
    },

    getTotalPaid: function () {
      var sum = 0;
      this.activeMethods.forEach(function (m) {
        sum += parseFloat(m.amount) || 0;
      });
      return Math.round(sum * 100) / 100;
    },

    render: function () {
      var format = window.PosFormat || {
        currency: function (v) { return (v || 0).toFixed(2) + ' Kz'; }
      };

      var totalPaid = this.getTotalPaid();
      var hasCash = this.activeMethods.some(function (m) { return m.id === 'cash'; });
      var diff = totalPaid - this.targetTotal;
      var change = (diff > 0 && hasCash) ? diff : 0;
      var due = diff < 0 ? Math.abs(diff) : 0;

      // Atualizar mostradores
      var totalEl = document.getElementById('checkout-total-due');
      var paidEl = document.getElementById('checkout-total-paid');
      var balanceLabelEl = document.getElementById('checkout-balance-label');
      var balanceValEl = document.getElementById('checkout-balance-value');
      var balanceRow = document.getElementById('checkout-balance-row');
      var btnConfirm = document.getElementById('btn-confirm-payment');

      if (totalEl) totalEl.textContent = format.currency(this.targetTotal);
      if (paidEl) paidEl.textContent = format.currency(totalPaid);

      if (balanceLabelEl && balanceValEl) {
        if (due > 0) {
          balanceLabelEl.textContent = 'Em Falta:';
          balanceValEl.textContent = format.currency(due);
          if (balanceRow) balanceRow.classList.add('has-due');
        } else {
          balanceLabelEl.textContent = 'Troco:';
          balanceValEl.textContent = format.currency(change);
          if (balanceRow) balanceRow.classList.remove('has-due');
        }
      }

      // Validação do NIF obrigatório para valores >= 50.000 Kz em consumidor final
      var nifAlert = document.getElementById('checkout-nif-alert');
      var client = window.PosCart ? window.PosCart.client : null;
      var isHighValueFinalConsumer = this.targetTotal >= 50000 && (!client || client.isFinalConsumer);

      if (nifAlert) {
        if (isHighValueFinalConsumer) {
          nifAlert.classList.add('is-visible');
        } else {
          nifAlert.classList.remove('is-visible');
        }
      }

      // Validar referências de pagamentos eletrónicos
      var missingElectronicRef = this.activeMethods.some(function (m) {
        return (m.id === 'tpa' || m.id === 'transfer') && (!m.ref || !m.ref.trim());
      });

      // Ativar / Desativar botão de confirmação
      if (btnConfirm) {
        var canConfirm = (totalPaid >= this.targetTotal) && !isHighValueFinalConsumer && !missingElectronicRef;
        btnConfirm.disabled = !canConfirm;
      }

      // Atualizar lista de métodos ativos no DOM
      var methodsContainer = document.getElementById('checkout-active-methods');
      if (methodsContainer) {
        var html = '';
        var self = this;
        this.activeMethods.forEach(function (m, idx) {
          var showRef = m.id === 'tpa' || m.id === 'transfer';
          html += '<div class="pos-active-method-row" data-method-id="' + m.id + '">' +
            '<span class="method-name">' + m.name + '</span>' +
            '<div style="display:flex; gap:6px;">' +
            '<input type="number" class="form-control method-amount-input" data-index="' + idx + '" value="' + m.amount + '" step="50" min="0" placeholder="Valor">' +
            (showRef ? '<input type="text" class="form-control method-ref-input" data-index="' + idx + '" value="' + (m.ref || '') + '" placeholder="N.º Transacção / Ref *">' : '') +
            '</div>' +
            (self.activeMethods.length > 1 ? '<button type="button" class="pos-btn-remove-method" data-index="' + idx + '" title="Remover meio"><i class="fa-solid fa-xmark"></i></button>' : '<div></div>') +
            '</div>';
        });
        methodsContainer.innerHTML = html;

        // Vincular eventos de alteração de valor e referência
        var amountInputs = methodsContainer.querySelectorAll('.method-amount-input');
        amountInputs.forEach(function (input) {
          input.addEventListener('input', function () {
            var i = parseInt(input.getAttribute('data-index'), 10);
            self.activeMethods[i].amount = parseFloat(input.value) || 0;
            self.render();
          });
        });

        var refInputs = methodsContainer.querySelectorAll('.method-ref-input');
        refInputs.forEach(function (input) {
          input.addEventListener('input', function () {
            var i = parseInt(input.getAttribute('data-index'), 10);
            self.activeMethods[i].ref = input.value;
            self.render();
          });
        });

        var removeBtns = methodsContainer.querySelectorAll('.pos-btn-remove-method');
        removeBtns.forEach(function (btn) {
          btn.addEventListener('click', function () {
            var i = parseInt(btn.getAttribute('data-index'), 10);
            self.activeMethods.splice(i, 1);
            self.render();
          });
        });
      }

      // Atualizar classes .is-active nos tiles de meios
      var tiles = document.querySelectorAll('.pos-method-tile');
      tiles.forEach(function (t) {
        var mId = t.getAttribute('data-method');
        var isActive = self.activeMethods.some(function (m) { return m.id === mId; });
        if (isActive) {
          t.classList.add('is-active');
        } else {
          t.classList.remove('is-active');
        }
      });
    },

    processPayment: function () {
      var btnConfirm = document.getElementById('btn-confirm-payment');
      if (!btnConfirm || btnConfirm.disabled) return;

      var originalHtml = btnConfirm.innerHTML;
      btnConfirm.disabled = true;
      btnConfirm.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A processar...';

      var salePayload = {
        total: this.targetTotal,
        paid: this.getTotalPaid(),
        payments: this.activeMethods,
        client: window.PosCart.client,
        items: window.PosCart.lines
      };

      var self = this;
      window.PosApi.createSale(salePayload).then(function (result) {
        btnConfirm.disabled = false;
        btnConfirm.innerHTML = originalHtml;

        if (window.closeModal) {
          window.closeModal('modal-checkout');
        }

        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Venda ' + result.document_number + ' concluída com sucesso!');
        }

        // Abrir talão térmico
        if (window.PosReceipt) {
          window.PosReceipt.show(result);
        }
      });
    }
  };

  window.PosCheckout = Checkout;
})(window, document);
