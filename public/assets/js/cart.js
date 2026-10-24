/**
 * ============================================================================
 * NETPOS - JS/POS/CART.JS
 * Gestão de estado do Carrinho de Vendas do POS:
 * - Regras fiscais: preços com IVA incluído, base = preço / (1 + taxa), imposto = preço - base
 * - Arredondamento a 2 casas decimais (meio para cima) por linha
 * - Leitura e cálculo de quantidades multiplicadas (ex.: "3*código")
 * - Verificação rigorosa de stock disponível com alerta toast
 * - Atualização reativa de toda a interface visual (Total gigante, rodapés e resumo)
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  var Cart = {
    lines: [],
    selectedIndex: -1,
    client: null,
    globalDiscountPct: 0,
    globalDiscountAmount: 0,

    /**
     * Inicializa o carrinho com o cliente por defeito
     */
    init: function () {
      if (window.PosCustomers) {
        this.client = window.PosCustomers.getFinalConsumer();
      }
      this.render();
    },

    /**
     * Adiciona um produto ao carrinho
     * @param {Object} product
     * @param {number} [quantity=1]
     * @returns {boolean}
     */
    addProduct: function (product, quantity) {
      if (!product) return false;
      var qtyToAdd = parseFloat(quantity) || 1;

      // 1. Verificar stock disponível
      if (product.stock <= 0) {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'Artigo sem stock disponível (' + product.name + ').');
        }
        return false;
      }

      // 2. Verificar se o artigo já existe na tabela de venda
      var existingIndex = this.lines.findIndex(function (line) {
        return line.product.id === product.id;
      });

      if (existingIndex >= 0) {
        var newTotalQty = this.lines[existingIndex].quantity + qtyToAdd;
        if (newTotalQty > product.stock) {
          if (typeof window.showToast === 'function') {
            window.showToast('danger', 'Stock insuficiente! Apenas ' + product.stock + ' unidades disponíveis.');
          }
          return false;
        }
        this.lines[existingIndex].quantity = newTotalQty;
        this.calculateLine(this.lines[existingIndex]);
        this.selectedIndex = existingIndex;
      } else {
        if (qtyToAdd > product.stock) {
          if (typeof window.showToast === 'function') {
            window.showToast('danger', 'Stock insuficiente! Apenas ' + product.stock + ' unidades disponíveis.');
          }
          return false;
        }

        var newLine = {
          product: product,
          quantity: qtyToAdd,
          unitPrice: product.price,
          discountPct: 0,
          discountAmount: 0,
          taxRate: product.taxRate !== undefined ? product.taxRate : 0.14,
          taxLabel: product.tax === 'ISENTO' ? 'Isento' : 'IVA 14%'
        };

        this.calculateLine(newLine);
        this.lines.push(newLine);
        this.selectedIndex = this.lines.length - 1;
      }

      this.render();
      if (typeof window.showToast === 'function') {
        window.showToast('info', product.name + ' adicionado ao carrinho.');
      }
      return true;
    },

    /**
     * Calcula os valores matemáticos e fiscais de uma linha individual
     * @param {Object} line
     */
    calculateLine: function (line) {
      var grossUnit = line.unitPrice;
      var rawTotal = line.quantity * grossUnit;

      // Desconto de linha
      var lineDiscount = 0;
      if (line.discountPct > 0) {
        lineDiscount = rawTotal * (line.discountPct / 100);
      } else if (line.discountAmount > 0) {
        lineDiscount = line.discountAmount;
      }
      line.discountAmount = Math.min(lineDiscount, rawTotal);

      var finalGrossTotal = Math.max(0, rawTotal - line.discountAmount);
      line.total = Math.round(finalGrossTotal * 100) / 100;

      // Decomposição fiscal da base e do IVA
      var taxMultiplier = 1 + line.taxRate;
      line.netTotal = Math.round((line.total / taxMultiplier) * 100) / 100;
      line.taxTotal = Math.round((line.total - line.netTotal) * 100) / 100;
    },

    /**
     * Processa a introdução de texto do leitor (ex.: "PRD-001", "3*PRD-001" ou código de barras)
     * @param {string} rawInput
     * @returns {boolean}
     */
    handleBarcodeScan: function (rawInput) {
      if (!rawInput || !rawInput.trim()) return false;
      var text = rawInput.trim();
      var qty = 1;
      var query = text;

      // Formato "X*CÓDIGO" para multiplicar quantidades
      if (text.includes('*')) {
        var parts = text.split('*');
        qty = parseFloat(parts[0]) || 1;
        query = parts[1].trim();
      }

      if (!window.PosProducts) return false;
      var product = window.PosProducts.findByCodeOrBarcode(query);

      if (product) {
        return this.addProduct(product, qty);
      } else {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'Artigo "' + query + '" não encontrado no sistema.');
        }
        return false;
      }
    },

    /**
     * Seleciona uma linha pelo seu índice
     * @param {number} index
     */
    selectLine: function (index) {
      if (index >= 0 && index < this.lines.length) {
        this.selectedIndex = index;
      } else {
        this.selectedIndex = -1;
      }
      this.render();
    },

    /**
     * Altera a quantidade da linha atualmente selecionada
     * @param {number} qty
     */
    updateSelectedQty: function (qty) {
      if (this.selectedIndex < 0 || !this.lines[this.selectedIndex]) return;
      var line = this.lines[this.selectedIndex];
      var newQty = parseFloat(qty);

      if (isNaN(newQty) || newQty <= 0) {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'A quantidade deve ser maior do que zero.');
        }
        return;
      }

      if (newQty > line.product.stock) {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'Stock insuficiente. Disponível: ' + line.product.stock);
        }
        return;
      }

      line.quantity = newQty;
      this.calculateLine(line);
      this.render();
      if (typeof window.showToast === 'function') {
        window.showToast('info', 'Quantidade alterada para ' + newQty);
      }
    },

    /**
     * Altera o preço unitário da linha selecionada
     * @param {number} price
     */
    updateSelectedPrice: function (price) {
      if (this.selectedIndex < 0 || !this.lines[this.selectedIndex]) return;
      var line = this.lines[this.selectedIndex];
      var newPrice = parseFloat(price);

      if (isNaN(newPrice) || newPrice < 0) return;

      line.unitPrice = newPrice;
      this.calculateLine(line);
      this.render();
      if (typeof window.showToast === 'function') {
        window.showToast('info', 'Preço do artigo atualizado.');
      }
    },

    /**
     * Aplica desconto à linha selecionada
     * @param {number} pct
     * @param {number} amount
     */
    applyLineDiscount: function (pct, amount) {
      if (this.selectedIndex < 0 || !this.lines[this.selectedIndex]) return;
      var line = this.lines[this.selectedIndex];

      if (pct !== undefined) {
        line.discountPct = Math.min(100, Math.max(0, parseFloat(pct) || 0));
        line.discountAmount = 0;
      } else if (amount !== undefined) {
        line.discountAmount = Math.max(0, parseFloat(amount) || 0);
        line.discountPct = 0;
      }

      this.calculateLine(line);
      this.render();
    },

    /**
     * Aplica desconto ao total global do documento
     * @param {number} pct
     * @param {number} amount
     */
    applyGlobalDiscount: function (pct, amount) {
      if (pct !== undefined) {
        this.globalDiscountPct = Math.min(100, Math.max(0, parseFloat(pct) || 0));
        this.globalDiscountAmount = 0;
      } else if (amount !== undefined) {
        this.globalDiscountAmount = Math.max(0, parseFloat(amount) || 0);
        this.globalDiscountPct = 0;
      }
      this.render();
    },

    /**
     * Elimina a linha selecionada do carrinho
     */
    deleteSelectedLine: function () {
      if (this.selectedIndex < 0 || !this.lines[this.selectedIndex]) {
        if (typeof window.showToast === 'function') {
          window.showToast('info', 'Por favor, selecione uma linha para eliminar.');
        }
        return;
      }

      var removed = this.lines.splice(this.selectedIndex, 1);
      this.selectedIndex = Math.min(this.selectedIndex, this.lines.length - 1);
      this.render();

      if (typeof window.showToast === 'function' && removed[0]) {
        window.showToast('danger', 'Linha de ' + removed[0].product.name + ' eliminada.');
      }
    },

    /**
     * Limpa o carrinho e inicia um novo documento de venda
     */
    clear: function () {
      this.lines = [];
      this.selectedIndex = -1;
      this.globalDiscountPct = 0;
      this.globalDiscountAmount = 0;
      if (window.PosCustomers) {
        this.client = window.PosCustomers.getFinalConsumer();
      }
      this.render();
    },

    /**
     * Define o cliente da venda
     * @param {Object} customer
     */
    setClient: function (customer) {
      if (!customer) return;
      this.client = customer;
      this.render();
      if (typeof window.showToast === 'function') {
        window.showToast('success', 'Cliente associado: ' + customer.name);
      }
    },

    /**
     * Calcula e devolve os totais gerais do carrinho
     * @returns {Object}
     */
    getTotals: function () {
      var linesGrossSum = 0;
      var linesNetSum = 0;
      var linesTaxSum = 0;
      var linesDiscountSum = 0;
      var totalItems = 0;

      this.lines.forEach(function (l) {
        linesGrossSum += l.total;
        linesNetSum += l.netTotal;
        linesTaxSum += l.taxTotal;
        linesDiscountSum += l.discountAmount;
        totalItems += l.quantity;
      });

      // Aplicação do desconto global sobre o total das linhas
      var extraDiscount = 0;
      if (this.globalDiscountPct > 0) {
        extraDiscount = linesGrossSum * (this.globalDiscountPct / 100);
      } else if (this.globalDiscountAmount > 0) {
        extraDiscount = this.globalDiscountAmount;
      }
      extraDiscount = Math.min(extraDiscount, linesGrossSum);

      var grandTotal = Math.max(0, linesGrossSum - extraDiscount);
      var totalDiscount = linesDiscountSum + extraDiscount;

      return {
        subtotal: linesNetSum,
        discount: totalDiscount,
        tax: linesTaxSum,
        total: grandTotal,
        itemsCount: totalItems
      };
    },

    /**
     * Atualiza toda a árvore DOM com o estado mais recente do carrinho
     */
    render: function () {
      var tbody = document.getElementById('pos-cart-tbody');
      var totalDisplay = document.getElementById('pos-total-display');
      var summaryDiscount = document.getElementById('pos-summary-discount');
      var summaryTax = document.getElementById('pos-summary-tax');
      var footerSubtotal = document.getElementById('pos-footer-subtotal');
      var footerDiscount = document.getElementById('pos-footer-discount');
      var footerTax = document.getElementById('pos-footer-tax');
      var footerTotal = document.getElementById('pos-footer-total');
      var statusItemCount = document.getElementById('pos-status-item-count');
      var clientNameEl = document.getElementById('pos-client-name');
      var clientNifEl = document.getElementById('pos-client-nif');

      var totals = this.getTotals();
      var format = window.PosFormat || {
        currency: function (v) { return (v || 0).toFixed(2) + ' Kz'; }
      };

      // Atualizar Totais no Ecrã
      if (totalDisplay) {
        totalDisplay.innerHTML = format.currency(totals.total, false) + ' <span class="pos-summary-currency">Kz</span>';
      }
      if (summaryDiscount) summaryDiscount.textContent = format.currency(totals.discount);
      if (summaryTax) summaryTax.textContent = format.currency(totals.tax);
      if (footerSubtotal) footerSubtotal.textContent = format.currency(totals.subtotal);
      if (footerDiscount) footerDiscount.textContent = format.currency(totals.discount);
      if (footerTax) footerTax.textContent = format.currency(totals.tax);
      if (footerTotal) footerTotal.textContent = format.currency(totals.total);
      if (statusItemCount) statusItemCount.textContent = totals.itemsCount;

      // Atualizar Cliente
      if (this.client) {
        if (clientNameEl) clientNameEl.textContent = this.client.name;
        if (clientNifEl) clientNifEl.textContent = '· ' + this.client.nif;
      }

      // Renderizar Linhas na Tabela
      if (tbody) {
        if (this.lines.length === 0) {
          tbody.innerHTML = '<tr><td colspan="7" class="pos-cart-empty">' +
            '<i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>' +
            '<div>Nenhum artigo registado no documento atual.</div>' +
            '<small style="color: #999;">Leia um código de barras ou pesquise pelo catálogo (F2).</small>' +
            '</td></tr>';
        } else {
          var html = '';
          var self = this;
          this.lines.forEach(function (line, index) {
            var isSelected = index === self.selectedIndex ? ' is-selected' : '';
            var taxBadgeClass = line.product.tax === 'ISENTO' ? 'pos-tax-badge is-exempt' : 'pos-tax-badge';

            html += '<tr class="pos-cart-row' + isSelected + '" data-line-index="' + index + '">' +
              '<td><strong>' + line.product.code + '</strong></td>' +
              '<td>' + line.product.name + '</td>' +
              '<td class="text-right">' + line.quantity.toFixed(2) + '</td>' +
              '<td class="text-right">' + format.currency(line.unitPrice) + '</td>' +
              '<td class="text-right">' + line.discountPct.toFixed(2) + '%</td>' +
              '<td class="text-center"><span class="' + taxBadgeClass + '">' + line.taxLabel + '</span></td>' +
              '<td class="text-right"><strong>' + format.currency(line.total) + '</strong></td>' +
              '</tr>';
          });
          tbody.innerHTML = html;
        }
      }

      // Disparar evento personalizado
      document.dispatchEvent(new CustomEvent('pos:cart-changed', { detail: { totals: totals } }));
    }
  };

  window.PosCart = Cart;
})(window, document);
