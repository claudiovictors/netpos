/**
 * ============================================================================
 * NETPOS - JS/POS/RECEIPT.JS
 * Construção, pré-visualização e impressão de Talões Térmicos (80mm e 58mm):
 * - Metadados fiscais certificados da República de Angola
 * - Decomposição do IVA por taxa (Isento e IVA 14%)
 * - Assinatura digital (Hash de 4 caracteres) e espaço para QR Code
 * - Transição automática do estado de submissão da AGT (Pendente -> Aceite)
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  var Receipt = {
    currentSale: null,
    format: '80mm', // '80mm' ou '58mm'

    init: function () {
      var self = this;

      // Alternar largura de papel térmico (80mm / 58mm)
      document.addEventListener('click', function (e) {
        var formatBtn = e.target.closest('[data-receipt-format]');
        if (formatBtn) {
          e.preventDefault();
          var fmt = formatBtn.getAttribute('data-receipt-format');
          self.setFormat(fmt);
        }
      });

      // Botão Imprimir Talão
      var btnPrint = document.getElementById('btn-print-receipt');
      if (btnPrint) {
        btnPrint.addEventListener('click', function (e) {
          e.preventDefault();
          window.print();
        });
      }

      // Botão Nova Venda
      var btnNewSale = document.getElementById('btn-receipt-new-sale');
      if (btnNewSale) {
        btnNewSale.addEventListener('click', function (e) {
          e.preventDefault();
          if (window.closeModal) {
            window.closeModal('modal-receipt');
          }
          if (window.PosCart) {
            window.PosCart.clear();
          }
        });
      }
    },

    setFormat: function (fmt) {
      this.format = fmt;
      var receiptEl = document.getElementById('pos-thermal-receipt-view');
      if (receiptEl) {
        if (fmt === '58mm') {
          receiptEl.classList.add('format-58mm');
        } else {
          receiptEl.classList.remove('format-58mm');
        }
      }
    },

    show: function (saleResult) {
      this.currentSale = saleResult;
      this.render();
      if (window.openModal) {
        window.openModal('modal-receipt');
      }

      // Simular envio para a AGT: passa de 'Pendente' a 'Aceite' após 2 segundos
      var chip = document.getElementById('receipt-agt-chip');
      if (chip) {
        chip.className = 'pos-receipt-agt-status pending';
        chip.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> AGT: Pendente de envio';

        setTimeout(function () {
          chip.className = 'pos-receipt-agt-status accepted';
          chip.innerHTML = '<i class="fa-solid fa-circle-check"></i> AGT: Aceite (simulado)';
        }, 2000);
      }
    },

    render: function () {
      var receiptEl = document.getElementById('pos-thermal-receipt-view');
      if (!receiptEl || !this.currentSale) return;

      var sale = this.currentSale;
      var data = sale.data || {};
      var client = data.client || { name: 'Consumidor Final', nif: '999999999' };
      var items = data.items || [];
      var payments = data.payments || [];
      var format = window.PosFormat || {
        currency: function (v) { return (v || 0).toFixed(2) + ' Kz'; },
        fullDatePt: function () { return new Date().toLocaleString('pt-PT'); }
      };

      // Decomposição das taxas de IVA
      var taxSummary = {
        exempt: { base: 0, tax: 0 },
        normal: { base: 0, tax: 0 }
      };

      var itemsHtml = '';
      items.forEach(function (l) {
        var isExempt = l.product.tax === 'ISENTO';
        if (isExempt) {
          taxSummary.exempt.base += l.netTotal;
        } else {
          taxSummary.normal.base += l.netTotal;
          taxSummary.normal.tax += l.taxTotal;
        }

        itemsHtml += '<tr>' +
          '<td>' + l.product.name + '<br><small>' + l.quantity.toFixed(2) + ' x ' + format.currency(l.unitPrice) + '</small></td>' +
          '<td class="text-right">' + format.currency(l.total) + '</td>' +
          '</tr>';
      });

      var paymentsHtml = '';
      payments.forEach(function (p) {
        var refInfo = p.ref ? ' (' + p.ref + ')' : '';
        paymentsHtml += '<div class="pos-receipt-total-row">' +
          '<span>' + p.name + refInfo + ':</span>' +
          '<span>' + format.currency(p.amount) + '</span>' +
          '</div>';
      });

      var grandTotal = data.total || 0;
      var totalPaid = data.paid || grandTotal;
      var change = Math.max(0, totalPaid - grandTotal);

      receiptEl.innerHTML =
        '<div class="pos-receipt-header">' +
        '<div class="pos-receipt-logo">NETPOS RETAIL</div>' +
        '<div class="pos-receipt-company">NetPos Soluções Comerciais, Lda.</div>' +
        '<div class="pos-receipt-meta">NIF: 541289301 · Reg. Comercial Luanda</div>' +
        '<div class="pos-receipt-meta">Rua Rainha Ginga, Edifício NetPos, Luanda</div>' +
        '<div class="pos-receipt-meta">Tel: +244 923 000 111 · info@netpos.ao</div>' +
        '</div>' +

        '<div class="pos-receipt-doc-title">FACTURA / RECIBO</div>' +
        '<div class="pos-receipt-doc-number">' + sale.document_number + '</div>' +

        '<div style="font-size: 10px; margin-bottom: 8px;">' +
        '<div><strong>Data/Hora:</strong> ' + new Date(sale.issued_at).toLocaleString('pt-PT') + '</div>' +
        '<div><strong>Operador:</strong> Administrador (Caixa #01)</div>' +
        '<div><strong>Cliente:</strong> ' + client.name + '</div>' +
        '<div><strong>NIF do Cliente:</strong> ' + client.nif + '</div>' +
        '</div>' +

        '<table class="pos-receipt-table">' +
        '<thead><tr><th>Artigo / Qtd.</th><th class="text-right">Total</th></tr></thead>' +
        '<tbody>' + itemsHtml + '</tbody>' +
        '</table>' +

        '<div class="pos-receipt-totals">' +
        '<div class="pos-receipt-total-row"><span>Subtotal:</span><span>' + format.currency(grandTotal - taxSummary.normal.tax) + '</span></div>' +
        '<div class="pos-receipt-total-row"><span>Total IVA:</span><span>' + format.currency(taxSummary.normal.tax) + '</span></div>' +
        '<div class="pos-receipt-total-row grand-total"><span>TOTAL:</span><span>' + format.currency(grandTotal) + '</span></div>' +
        paymentsHtml +
        (change > 0 ? '<div class="pos-receipt-total-row"><span>Troco Entregue:</span><span>' + format.currency(change) + '</span></div>' : '') +
        '</div>' +

        '<table class="pos-receipt-tax-table">' +
        '<thead><tr><th>Taxa</th><th class="text-right">Incidência</th><th class="text-right">Imposto</th></tr></thead>' +
        '<tbody>' +
        (taxSummary.normal.base > 0 ? '<tr><td>IVA Geral (14%)</td><td class="text-right">' + format.currency(taxSummary.normal.base) + '</td><td class="text-right">' + format.currency(taxSummary.normal.tax) + '</td></tr>' : '') +
        (taxSummary.exempt.base > 0 ? '<tr><td>Isento (0%)</td><td class="text-right">' + format.currency(taxSummary.exempt.base) + '</td><td class="text-right">0,00 Kz</td></tr>' : '') +
        '</tbody>' +
        '</table>' +

        '<div class="pos-receipt-fiscal-footer">' +
        '<!-- Processado por programa validado n.º 999/AGT/2026 -->' +
        '<div>Processado por programa validado n.º 999/AGT/2026</div>' +
        '<div class="pos-receipt-hash">' + sale.hash + ' - Assinatura Digital NetPos</div>' +
        '<div class="pos-receipt-qrcode">' +
        '<svg viewBox="0 0 100 100" width="80" height="80"><rect width="100" height="100" fill="#ffffff"/><rect x="10" y="10" width="30" height="30" fill="#000000"/><rect x="15" y="15" width="20" height="20" fill="#ffffff"/><rect x="60" y="10" width="30" height="30" fill="#000000"/><rect x="65" y="15" width="20" height="20" fill="#ffffff"/><rect x="10" y="60" width="30" height="30" fill="#000000"/><rect x="15" y="65" width="20" height="20" fill="#ffffff"/><rect x="45" y="45" width="10" height="10" fill="#000000"/><rect x="50" y="60" width="15" height="15" fill="#000000"/><rect x="70" y="70" width="20" height="20" fill="#000000"/></svg>' +
        '</div>' +
        '<small>Obrigado pela sua preferência!</small>' +
        '</div>';
    }
  };

  window.PosReceipt = Receipt;
})(window, document);
