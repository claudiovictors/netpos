/**
 * ============================================================================
 * NETPOS - JS/POS/POS.JS
 * Orquestrador principal do POS.
 *
 * ÍNDICE
 *  1. Relógio
 *  2. Alternância de vistas (tiles / outras opções) + botões simples
 *  3. Estado AGT (demo)
 *  4. Código de barras
 *  5. Seleção de linhas do carrinho
 *  6. Teclas de atalho
 *  7. Teclado numérico genérico (data-keypad-for)
 *  8. Modal Pesquisa de Produtos
 *  9. Modal Clientes
 * 10. Modal Quantidade
 * 11. Modal Preço (pede PIN)
 * 12. Modal Desconto (pede PIN se for elevado)
 * 13. Modal PIN de Gerente
 *
 * NOTA: a abertura dos modais por clique é feita pelo modal.js através do
 * atributo data-modal-open. Aqui só se abrem modais por atalho de teclado
 * ou por lógica (ex.: PIN).
 * ============================================================================
 */
(function (window, document) {
  'use strict';

  /* ===== CONFIGURAÇÃO ===== */
  var MANAGER_PIN = '1234';        // mock; em produção validar no servidor Laravel
  var DISCOUNT_PCT_LIMIT = 10;     // % acima do qual pede PIN
  var DISCOUNT_VALUE_LIMIT = 5000; // Kz acima do qual pede PIN

  /* ===== HELPERS ===== */
  function $(id) { return document.getElementById(id); }

  function toast(type, msg) {
    if (typeof window.showToast === 'function') window.showToast(type, msg);
  }

  function open(id) { if (window.openModal) window.openModal(id); }
  function close(id) { if (window.closeModal) window.closeModal(id); }

  function isAnyModalOpen() {
    return !!document.querySelector('.pos-modal.is-open, .pos-lock-overlay.is-open');
  }

  function formatMoney(v) {
    if (window.PosFormat && typeof window.PosFormat.currency === 'function') {
      return window.PosFormat.currency(v);
    }
    return Number(v).toFixed(2) + ' Kz';
  }

  function getSelectedLine() {
    var cart = window.PosCart;
    if (!cart || cart.selectedIndex < 0) return null;
    return cart.lines[cart.selectedIndex] || null;
  }

  /* ===== ARRANQUE ===== */
  document.addEventListener('DOMContentLoaded', function () {
    // Módulos de negócio
    if (window.PosCart) window.PosCart.init();
    if (window.PosCheckout) window.PosCheckout.init();
    if (window.PosReceipt) window.PosReceipt.init();
    if (window.PosSession) window.PosSession.init();
    if (window.PosDocuments) window.PosDocuments.init();

    // Ecrã
    initClock();
    initViewSwitching();
    initAgtStatusDemo();
    initBarcodeEntry();
    initRowSelection();
    initShortcuts();

    // Modais
    initKeypads();
    initProductSearchModal();
    initCustomerModal();
    initQuantityModal();
    initPriceModal();
    initDiscountModal();
    initPinModal();
  });

  /* ===== 1. RELÓGIO ===== */
  function initClock() {
    var el = $('pos-live-clock');
    if (!el) return;

    // Mantém o ícone e atualiza só o <span> do texto
    var textEl = el.querySelector('span') || el;

    function tick() {
      var now = new Date();
      textEl.textContent = (window.PosFormat && typeof window.PosFormat.fullDatePt === 'function')
        ? window.PosFormat.fullDatePt(now)
        : now.toLocaleString('pt-PT');
    }
    tick();
    setInterval(tick, 1000);
  }

  /* ===== 2. VISTAS E BOTÕES SIMPLES ===== */
  function initViewSwitching() {
    var viewTiles = $('pos-view-tiles');
    var viewMore = $('pos-view-more');

    function showMore() {
      if (!viewTiles || !viewMore) return;
      viewTiles.classList.add('is-hidden');
      viewMore.classList.remove('is-hidden');
    }
    function showTiles() {
      if (!viewTiles || !viewMore) return;
      viewMore.classList.add('is-hidden');
      viewTiles.classList.remove('is-hidden');
    }

    var btnMore = $('btn-show-more-options');
    var btnBack = $('btn-back-to-tiles');
    var toggle = $('pos-topbar-toggle');

    if (btnMore) btnMore.addEventListener('click', function (e) { e.preventDefault(); showMore(); });
    if (btnBack) btnBack.addEventListener('click', function (e) { e.preventDefault(); showTiles(); });
    if (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        if (viewMore && viewMore.classList.contains('is-hidden')) showMore();
        else showTiles();
      });
    }

    // Novo documento
    var btnNew = $('btn-new-doc');
    if (btnNew) {
      btnNew.addEventListener('click', function (e) {
        e.preventDefault();
        if (!window.PosCart) return;
        if (window.PosCart.lines.length > 0 &&
            !confirm('Iniciar um novo documento e limpar a venda atual?')) return;
        window.PosCart.clear();
        toast('info', 'Novo documento iniciado.');
      });
    }

    // Eliminar linha
    var btnDel = $('btn-delete-line');
    if (btnDel) {
      btnDel.addEventListener('click', function (e) {
        e.preventDefault();
        if (window.PosCart) window.PosCart.deleteSelectedLine();
      });
    }
  }

  /* ===== 3. ESTADO AGT (demo) ===== */
  function initAgtStatusDemo() {
    var badge = $('pos-agt-badge');
    var text = $('pos-agt-text');
    if (!badge) return;

    var states = [
      { cls: 'status-connected', text: 'AGT Online', toast: 'success', msg: 'Comunicação com a AGT operacional.' },
      { cls: 'status-pending', text: 'AGT Pendente (2)', toast: 'warning', msg: 'Existem 2 documentos pendentes de transmissão para a AGT.' },
      { cls: 'status-offline', text: 'AGT Offline', toast: 'danger', msg: 'Sistema a operar em contingência offline da AGT.' }
    ];
    var index = 0;

    badge.addEventListener('click', function (e) {
      e.preventDefault();
      states.forEach(function (s) { badge.classList.remove(s.cls); });
      index = (index + 1) % states.length;
      var s = states[index];
      badge.classList.add(s.cls);
      if (text) text.textContent = s.text;
      toast(s.toast, s.msg);
    });
  }

  /* ===== 4. CÓDIGO DE BARRAS ===== */
  function initBarcodeEntry() {
    var input = $('barcode-input');
    if (!input) return;

    input.addEventListener('keydown', function (e) {
      if (e.key !== 'Enter') return;
      e.preventDefault();
      var val = input.value;
      if (val && window.PosCart && window.PosCart.handleBarcodeScan(val)) {
        input.value = '';
      }
    });

    setTimeout(function () { input.focus(); }, 150);

    // Clique em zona "morta" devolve o foco ao leitor
    document.addEventListener('click', function (e) {
      if (isAnyModalOpen()) return;
      var interactive = e.target.closest(
        'button, input, select, textarea, a, .pos-tile-btn, .pos-list-btn, .pos-info-item-clickable'
      );
      if (!interactive) input.focus();
    });
  }

  /* ===== 5. SELEÇÃO DE LINHAS ===== */
  function initRowSelection() {
    var tbody = $('pos-cart-tbody');
    if (!tbody) return;

    tbody.addEventListener('click', function (e) {
      var row = e.target.closest('tr.pos-cart-row');
      if (!row || !window.PosCart) return;
      var index = parseInt(row.getAttribute('data-line-index'), 10);
      if (!isNaN(index)) window.PosCart.selectLine(index);
    });
  }

  /* ===== 6. ATALHOS DE TECLADO ===== */
  function initShortcuts() {
    // Atalho -> modal a abrir
    var modalKeys = {
      F2: 'modal-search-product',
      F3: 'modal-quantity',
      F4: 'modal-customer',
      F6: 'modal-price',
      F7: 'modal-discount'
    };

    document.addEventListener('keydown', function (e) {
      // Com um modal aberto, os atalhos ficam desativados (exceto Esc, tratado no modal.js)
      if (isAnyModalOpen()) return;

      if (modalKeys[e.key]) {
        e.preventDefault();
        open(modalKeys[e.key]);
        return;
      }

      switch (e.key) {
        case 'F8':
          e.preventDefault();
          if (window.PosDocuments) window.PosDocuments.parkCurrentSale();
          return;
        case 'F9':
          e.preventDefault();
          open('modal-checkout');
          return;
        case 'F10':
          e.preventDefault();
          var toggle = $('pos-topbar-toggle');
          if (toggle) toggle.click();
          return;
        case 'Delete':
          // Não apaga a linha se o utilizador estiver a escrever num campo
          if (e.target.closest('input, textarea, select')) return;
          e.preventDefault();
          if (window.PosCart) window.PosCart.deleteSelectedLine();
          return;
      }

      // Ctrl + L
      if (e.ctrlKey && (e.key === 'l' || e.key === 'L')) {
        e.preventDefault();
        if (window.PosSession) window.PosSession.lock();
      }
    });
  }

  /* ===== 7. TECLADO NUMÉRICO GENÉRICO =====
   * Qualquer .pos-keypad-grid[data-keypad-for="id-do-input"] escreve nesse input.
   * (Usado no Preço, Desconto e PIN.) */
  function initKeypads() {
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.pos-keypad-grid[data-keypad-for] .pos-keypad-btn');
      if (!btn) return;
      e.preventDefault();

      var grid = btn.closest('.pos-keypad-grid');
      var input = $(grid.getAttribute('data-keypad-for'));
      if (!input) return;

      var current = String(input.value || '');
      var max = parseInt(input.getAttribute('maxlength'), 10) || 12;

      if (btn.classList.contains('btn-clear')) {
        input.value = '';
      } else if (btn.classList.contains('btn-backspace')) {
        input.value = current.slice(0, -1);
      } else {
        var ch = btn.textContent.trim();
        if (/^\d$/.test(ch) && current.length < max) input.value = current + ch;
      }
      input.dispatchEvent(new Event('input', { bubbles: true }));
    });
  }

  /* ===== 8. MODAL PESQUISA DE PRODUTOS =====
   * As linhas vêm do Blade; aqui só filtramos (show/hide) e selecionamos. */
  function initProductSearchModal() {
    var searchInput = $('product-search-input');
    var categoryFilter = $('product-category-filter');
    var tbody = $('product-search-results');
    var btnAdd = $('btn-add-selected-product');
    if (!tbody) return;

    var rows = tbody.querySelectorAll('.product-row');
    var selectedId = null;

    function applyFilter() {
      var term = searchInput ? searchInput.value.trim().toLowerCase() : '';
      var cat = categoryFilter ? categoryFilter.value : '';

      rows.forEach(function (row) {
        var d = row.dataset;
        var okTerm = !term ||
          d.name.toLowerCase().includes(term) ||
          d.code.toLowerCase().includes(term) ||
          (d.barcode || '').includes(term);
        var okCat = !cat || d.category === cat;
        row.style.display = (okTerm && okCat) ? '' : 'none';
      });
    }

    function selectRow(row) {
      rows.forEach(function (r) { r.classList.remove('is-selected'); });
      row.classList.add('is-selected');
      selectedId = parseInt(row.dataset.id, 10);
    }

    function addToCart(productId) {
      var product = window.PosProducts && window.PosProducts.findById(productId);
      if (!product || !window.PosCart) return;
      if (product.stock <= 0) {
        toast('warning', 'Artigo sem stock disponível.');
        return;
      }
      window.PosCart.addProduct(product, 1);
      close('modal-search-product');
    }

    if (searchInput) searchInput.addEventListener('input', applyFilter);
    if (categoryFilter) categoryFilter.addEventListener('change', applyFilter);

    tbody.addEventListener('click', function (e) {
      var row = e.target.closest('.product-row');
      if (row) selectRow(row);
    });

    tbody.addEventListener('dblclick', function (e) {
      var row = e.target.closest('.product-row');
      if (row) addToCart(parseInt(row.dataset.id, 10));
    });

    if (btnAdd) {
      btnAdd.addEventListener('click', function () {
        if (!selectedId) { toast('info', 'Selecione um artigo da lista.'); return; }
        addToCart(selectedId);
      });
    }

    // Ao abrir: limpa pesquisa e seleção
    document.addEventListener('pos:modal-opened', function (e) {
      if (!e.detail || e.detail.modalId !== 'modal-search-product') return;
      if (searchInput) searchInput.value = '';
      if (categoryFilter) categoryFilter.value = '';
      selectedId = null;
      rows.forEach(function (r) { r.classList.remove('is-selected'); });
      applyFilter();
    });
  }

  /* ===== 9. MODAL CLIENTES ===== */
  function initCustomerModal() {
    var searchInput = $('customer-search-input');
    var tbody = $('customer-search-results');
    var btnSelect = $('btn-select-customer');
    var selectedId = 1;

    function render() {
      if (!window.PosCustomers || !tbody) return; // sem customers.js mantém o HTML estático
      var list = window.PosCustomers.search(searchInput ? searchInput.value : '');
      tbody.innerHTML = list.map(function (c) {
        var sel = c.id === selectedId ? ' class="is-selected"' : '';
        return '<tr data-id="' + c.id + '"' + sel + ' style="cursor:pointer;">' +
          '<td><strong>' + c.name + '</strong></td>' +
          '<td>' + c.nif + '</td>' +
          '<td>' + c.phone + '</td></tr>';
      }).join('');
    }

    function apply(id) {
      var cust = window.PosCustomers && window.PosCustomers.findById(id);
      if (cust && window.PosCart) {
        window.PosCart.setClient(cust);
        close('modal-customer');
      }
    }

    if (searchInput) searchInput.addEventListener('input', render);

    if (tbody) {
      tbody.addEventListener('click', function (e) {
        var tr = e.target.closest('tr[data-id]');
        if (!tr) return;
        selectedId = parseInt(tr.getAttribute('data-id'), 10);
        render();
      });
      tbody.addEventListener('dblclick', function (e) {
        var tr = e.target.closest('tr[data-id]');
        if (tr) apply(parseInt(tr.getAttribute('data-id'), 10));
      });
    }

    if (btnSelect) btnSelect.addEventListener('click', function () { apply(selectedId); });

    document.addEventListener('pos:modal-opened', function (e) {
      if (e.detail && e.detail.modalId === 'modal-customer') render();
    });
  }

  /* ===== 10. MODAL QUANTIDADE ===== */
  function initQuantityModal() {
    var display = $('keypad-qty-display');
    var btnConfirm = $('btn-confirm-qty');
    var container = document.querySelector('#modal-quantity .pos-keypad-container');

    if (container && display && window.PosKeypad) {
      window.PosKeypad.bind(container, display, { defaultValue: '1' });
    }

    if (btnConfirm) {
      btnConfirm.addEventListener('click', function () {
        var qty = parseFloat(String(display.textContent).replace(',', '.'));
        if (!qty || qty <= 0) { toast('warning', 'Quantidade inválida.'); return; }
        if (window.PosCart) window.PosCart.updateSelectedQty(qty);
        close('modal-quantity');
      });
    }

    document.addEventListener('pos:modal-opened', function (e) {
      if (!e.detail || e.detail.modalId !== 'modal-quantity') return;
      var line = getSelectedLine();
      if (line && display) display.textContent = String(line.quantity);
    });
  }

  /* ===== 11. MODAL PREÇO (requer PIN) ===== */
  function initPriceModal() {
    var input = $('input-modal-price');
    var nameEl = $('price-target-item-name');
    var btnConfirm = $('btn-confirm-price');

    document.addEventListener('pos:modal-opened', function (e) {
      if (!e.detail || e.detail.modalId !== 'modal-price') return;
      var line = getSelectedLine();
      if (nameEl) nameEl.textContent = line ? (line.ref || '') + ' · ' + (line.description || line.name || '') : 'Nenhuma linha selecionada';
      if (input) input.value = line ? line.price : '';
    });

    if (btnConfirm) {
      btnConfirm.addEventListener('click', function () {
        var newPrice = parseFloat(input.value);
        if (isNaN(newPrice) || newPrice < 0) { toast('warning', 'Preço inválido.'); return; }
        if (!getSelectedLine()) { toast('warning', 'Selecione uma linha do carrinho.'); return; }

        askManagerPin('Autorização para alteração de preço', function () {
          if (window.PosCart) window.PosCart.updateSelectedPrice(newPrice);
        });
      });
    }
  }

  /* ===== 12. MODAL DESCONTO =====
   * Escopo: 'line' (linha) | 'global' (total)
   * Tipo:   'percent' (%)  | 'fixed' (Kz) */
  function initDiscountModal() {
    var input = $('input-modal-discount');
    var btnConfirm = $('btn-confirm-discount');
    var state = { scope: 'line', type: 'percent' };

    var scopeBtns = { line: $('btn-discount-scope-line'), global: $('btn-discount-scope-global') };
    var typeBtns = { percent: $('btn-discount-type-percent'), fixed: $('btn-discount-type-fixed') };

    /** Marca visualmente o botão ativo de um grupo */
    function setActive(group, active) {
      Object.keys(group).forEach(function (key) {
        var b = group[key];
        if (!b) return;
        var on = key === active;
        b.classList.toggle('pos-btn-primary', on);
        b.classList.toggle('pos-btn-default', !on);
      });
    }

    Object.keys(scopeBtns).forEach(function (k) {
      if (scopeBtns[k]) scopeBtns[k].addEventListener('click', function () {
        state.scope = k; setActive(scopeBtns, k);
      });
    });
    Object.keys(typeBtns).forEach(function (k) {
      if (typeBtns[k]) typeBtns[k].addEventListener('click', function () {
        state.type = k; setActive(typeBtns, k);
      });
    });

    document.addEventListener('pos:modal-opened', function (e) {
      if (!e.detail || e.detail.modalId !== 'modal-discount') return;
      state.scope = 'line'; state.type = 'percent';
      setActive(scopeBtns, 'line'); setActive(typeBtns, 'percent');
      if (input) input.value = '5';
    });

    if (btnConfirm) {
      btnConfirm.addEventListener('click', function () {
        var value = parseFloat(input.value);
        if (isNaN(value) || value <= 0) { toast('warning', 'Introduza um valor de desconto.'); return; }
        if (state.type === 'percent' && value > 100) { toast('warning', 'Percentagem máxima: 100%.'); return; }
        if (state.scope === 'line' && !getSelectedLine()) { toast('warning', 'Selecione uma linha do carrinho.'); return; }

        var pct = state.type === 'percent' ? value : undefined;
        var amt = state.type === 'fixed' ? value : undefined;
        var needsPin = (pct !== undefined && pct > DISCOUNT_PCT_LIMIT) ||
                       (amt !== undefined && amt > DISCOUNT_VALUE_LIMIT);

        function apply() {
          if (!window.PosCart) return;
          if (state.scope === 'line') window.PosCart.applyLineDiscount(pct, amt);
          else window.PosCart.applyGlobalDiscount(pct, amt);
          close('modal-discount');
        }

        if (needsPin) askManagerPin('Autorização para desconto elevado', apply);
        else apply();
      });
    }
  }

  /* ===== 13. PIN DE GERENTE =====
   * O PIN só abre quando alguém chama askManagerPin(). */
  var pendingPinCallback = null;

  function askManagerPin(reasonText, onSuccess) {
    pendingPinCallback = onSuccess;

    var reasonEl = $('pin-prompt-message');
    var pinInput = $('input-modal-pin');
    var helpEl = $('pin-error-help');

    if (reasonEl) reasonEl.textContent = (reasonText || 'Autorização de gerente requerida') + '. Introduza o PIN de supervisor.';
    if (pinInput) pinInput.value = '';
    if (helpEl) helpEl.textContent = '';

    open('modal-pin');
  }

  function initPinModal() {
    var pinInput = $('input-modal-pin');
    var helpEl = $('pin-error-help');
    var btnConfirm = $('btn-confirm-pin');
    if (!btnConfirm || !pinInput) return;

    function validate() {
      if (pinInput.value === MANAGER_PIN) {
        var cb = pendingPinCallback;
        pendingPinCallback = null;
        close('modal-pin');
        if (typeof cb === 'function') cb();
      } else {
        if (helpEl) helpEl.textContent = 'PIN incorreto.';
        pinInput.value = '';
        pinInput.focus();
      }
    }

    btnConfirm.addEventListener('click', validate);
    pinInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); validate(); }
    });

    // Se o PIN for cancelado/fechado, descarta a ação pendente
    document.addEventListener('pos:modal-closed', function (e) {
      if (e.detail && e.detail.modalId === 'modal-pin' && pinInput.value !== MANAGER_PIN) {
        pendingPinCallback = null;
      }
    });
  }

  window.askManagerPin = askManagerPin;
})(window, document);