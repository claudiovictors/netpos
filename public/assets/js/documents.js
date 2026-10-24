/**
 * ============================================================================
 * NETPOS - JS/POS/DOCUMENTS.JS
 * Gestão de documentos comerciais e operações fiscais do POS:
 * - Conversão de Documentos (Orçamentos, Pró-formas para Venda, igual à Imagem 2)
 * - Emissão de Notas de Crédito / Devoluções por referência de documento
 * - Gravação e recuperação de documentos suspensos (vendas temporárias)
 * - Informação detalhada do documento e anulação com PIN de gerente
 * ============================================================================
 */

(function (window, document) {
  'use strict';

  var Documents = {
    init: function () {
      var self = this;

      // 1. Gravar Temporário (Tile)
      var btnPark = document.getElementById('btn-park-doc');
      if (btnPark) {
        btnPark.addEventListener('click', function (e) {
          e.preventDefault();
          self.parkCurrentSale();
        });
      }

      // 2. Recuperar Documento (Tile)
      var btnRecover = document.getElementById('btn-recover-doc');
      if (btnRecover) {
        btnRecover.addEventListener('click', function (e) {
          e.preventDefault();
          self.loadParkedSalesList();
        });
      }

      // 3. Eliminar Documento em Curso (Descartar)
      var btnConfirmDiscard = document.getElementById('btn-confirm-discard-doc');
      if (btnConfirmDiscard) {
        btnConfirmDiscard.addEventListener('click', function (e) {
          e.preventDefault();
          if (window.PosCart) {
            window.PosCart.clear();
          }
          if (window.closeModal) {
            window.closeModal('modal-discard');
          }
          if (typeof window.showToast === 'function') {
            window.showToast('danger', 'Documento descartado. Carrinho limpo.');
          }
        });
      }

      // 4. Conversão de Documento (Seguinte)
      var btnConfirmConvert = document.getElementById('btn-confirm-convert');
      if (btnConfirmConvert) {
        btnConfirmConvert.addEventListener('click', function (e) {
          e.preventDefault();
          self.handleConvertDocument();
        });
      }

      // 5. Emissão de Nota de Crédito
      var btnConfirmReturn = document.getElementById('btn-confirm-return');
      if (btnConfirmReturn) {
        btnConfirmReturn.addEventListener('click', function (e) {
          e.preventDefault();
          self.handleCreateCreditNote();
        });
      }

      // 6. Anulação de Documento
      var btnConfirmVoid = document.getElementById('btn-confirm-void-doc');
      if (btnConfirmVoid) {
        btnConfirmVoid.addEventListener('click', function (e) {
          e.preventDefault();
          self.handleVoidDocument();
        });
      }

      // 7. Reimprimir Último Talão
      var btnPrintLast = document.getElementById('btn-print-last');
      if (btnPrintLast) {
        btnPrintLast.addEventListener('click', function (e) {
          e.preventDefault();
          self.reprintLastDocument();
        });
      }
    },

    parkCurrentSale: function () {
      if (!window.PosCart || window.PosCart.lines.length === 0) {
        if (typeof window.showToast === 'function') {
          window.showToast('info', 'O carrinho está vazio. Nada para suspender.');
        }
        return;
      }

      var totals = window.PosCart.getTotals();
      var saleData = {
        client: window.PosCart.client,
        items: window.PosCart.lines,
        total: totals.total
      };

      window.PosApi.parkSale(saleData).then(function () {
        window.PosCart.clear();
        if (typeof window.showToast === 'function') {
          window.showToast('warning', 'Venda gravada temporariamente com sucesso!');
        }
      });
    },

    loadParkedSalesList: function () {
      window.PosApi.getParkedSales().then(function (parkedList) {
        var tbody = document.getElementById('parked-sales-tbody');
        if (!tbody) return;

        var format = window.PosFormat || {
          currency: function (v) { return (v || 0).toFixed(2) + ' Kz'; }
        };

        if (parkedList.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px; color:#999;">Nenhum documento temporário suspenso.</td></tr>';
          return;
        }

        var html = '';
        parkedList.forEach(function (p) {
          html += '<tr>' +
            '<td><strong>#' + String(p.id).slice(-4) + '</strong></td>' +
            '<td>' + (p.client ? p.client.name : 'Consumidor Final') + '</td>' +
            '<td>' + p.items_count + ' artigo(s)</td>' +
            '<td class="text-right"><strong>' + format.currency(p.total) + '</strong></td>' +
            '<td class="text-center">' +
            '<button type="button" class="pos-btn pos-btn-primary btn-restore-parked" data-id="' + p.id + '" style="min-height:30px; padding:3px 8px; font-size:11px; margin-right:4px;">Recuperar</button>' +
            '<button type="button" class="pos-btn pos-btn-danger btn-delete-parked" data-id="' + p.id + '" style="min-height:30px; padding:3px 8px; font-size:11px;">Eliminar</button>' +
            '</td>' +
            '</tr>';
        });
        tbody.innerHTML = html;

        // Ações de recuperar e eliminar
        var restoreBtns = tbody.querySelectorAll('.btn-restore-parked');
        restoreBtns.forEach(function (b) {
          b.addEventListener('click', function () {
            var id = parseInt(b.getAttribute('data-id'), 10);
            var item = parkedList.find(function (p) { return p.id === id; });
            if (item) {
              if (window.PosCart.lines.length > 0) {
                if (!confirm('O carrinho atual tem artigos. Substituir pela venda temporária?')) {
                  return;
                }
              }
              window.PosCart.lines = item.items;
              window.PosCart.client = item.client;
              window.PosCart.render();
              window.PosApi.deleteParkedSale(id);
              if (window.closeModal) window.closeModal('modal-parked');
              if (typeof window.showToast === 'function') {
                window.showToast('success', 'Documento temporário recuperado!');
              }
            }
          });
        });

        var delBtns = tbody.querySelectorAll('.btn-delete-parked');
        delBtns.forEach(function (b) {
          b.addEventListener('click', function () {
            var id = parseInt(b.getAttribute('data-id'), 10);
            window.PosApi.deleteParkedSale(id).then(function () {
              Documents.loadParkedSalesList();
              if (typeof window.showToast === 'function') {
                window.showToast('danger', 'Documento temporário removido.');
              }
            });
          });
        });
      });
    },

    handleConvertDocument: function () {
      var btn = document.getElementById('btn-confirm-convert');
      if (!btn) return;

      var originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A converter...';

      window.PosApi.convertDocument('ORC-1').then(function (res) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (window.PosProducts && window.PosCart) {
          res.items.forEach(function (it) {
            var prod = window.PosProducts.findByCodeOrBarcode(it.code);
            if (prod) {
              window.PosCart.addProduct(prod, it.quantity);
            }
          });
        }

        if (window.closeModal) {
          window.closeModal('modal-convert');
        }

        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Orçamento ORC 1/1 convertido para o carrinho com sucesso!');
        }
      });
    },

    handleCreateCreditNote: function () {
      var btn = document.getElementById('btn-confirm-return');
      var reasonInput = document.getElementById('return-reason-text');
      if (!btn) return;

      if (!reasonInput || !reasonInput.value.trim()) {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'O motivo da devolução é obrigatório por lei.');
        }
        if (reasonInput) reasonInput.classList.add('has-error');
        return;
      }

      var originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A emitir NC...';

      window.PosApi.createCreditNote({
        total_refunded: 20000.00,
        reason: reasonInput.value
      }).then(function (nc) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (window.closeModal) {
          window.closeModal('modal-return');
        }

        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Nota de Crédito ' + nc.document_number + ' emitida com sucesso!');
        }
      });
    },

    handleVoidDocument: function () {
      var btn = document.getElementById('btn-confirm-void-doc');
      var reasonInput = document.getElementById('void-reason');
      var pinInput = document.getElementById('void-pin');
      if (!btn) return;

      if (!reasonInput || !reasonInput.value.trim()) {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'O motivo da anulação é obrigatório.');
        }
        return;
      }

      if (!pinInput || pinInput.value !== '1234') {
        if (typeof window.showToast === 'function') {
          window.showToast('danger', 'PIN de gerente incorreto (PIN mock: 1234).');
        }
        return;
      }

      var originalHtml = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A anular...';

      window.PosApi.voidDocument('FR A2026/000124', reasonInput.value, pinInput.value).then(function (res) {
        btn.disabled = false;
        btn.innerHTML = originalHtml;

        if (window.closeModal) {
          window.closeModal('modal-doc-info');
        }

        if (typeof window.showToast === 'function') {
          window.showToast('danger', res.message);
        }
      });
    },

    reprintLastDocument: function () {
      var last = localStorage.getItem('netpos_last_issued_doc');
      if (!last) {
        if (typeof window.showToast === 'function') {
          window.showToast('info', 'Não existe nenhum documento emitido recentemente.');
        }
        return;
      }

      try {
        var parsed = JSON.parse(last);
        if (window.PosReceipt) {
          window.PosReceipt.show(parsed);
        }
      } catch (e) {}
    }
  };

  window.PosDocuments = Documents;
})(window, document);
