/**
 * ==========================================================================
 * NETPOS - APP.JS
 * Lógica geral da aplicação, interações de tabelas, tabs, filtros,
 * controlo de visibilidade de palavra-passe e medidor de força (estilo WordPress)
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  /* ===== 1. MOSTRAR / OCULTAR PALAVRA-PASSE (ESTILO WORDPRESS WP-LOGIN) ===== */
  /**
   * Alterna entre type="password" e type="text", comutando o ícone
   * (fa-eye / fa-eye-slash) e actualizando o atributo aria-label
   */
  var togglePasswordBtns = document.querySelectorAll('[data-action="toggle-password"], .wp-hide-pw, .btn-toggle-password');

  togglePasswordBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();

      // Identifica o campo alvo através do atributo data-target ou procura o input no mesmo contentor
      var targetInputId = btn.getAttribute('data-target');
      var input = targetInputId ? document.getElementById(targetInputId) : btn.closest('.password-input-wrapper, .user-pass-wrap').querySelector('input');

      if (!input) return;

      var icon = btn.querySelector('i');
      var isPassword = input.type === 'password';

      if (isPassword) {
        input.type = 'text';
        btn.setAttribute('aria-label', 'Ocultar palavra-passe');
        if (icon) {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        }
      } else {
        input.type = 'password';
        btn.setAttribute('aria-label', 'Mostrar palavra-passe');
        if (icon) {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        }
      }

      // Mantém o foco no campo para conforto do utilizador
      input.focus();
    });
  });

  /* ===== 2. MEDIDOR DE FORÇA DA PALAVRA-PASSE (ESTILO WORDPRESS) ===== */
  /**
   * Avalia o cumprimento, maiúsculas, minúsculas, dígitos e símbolos.
   * Apresenta estados com cores sólidas e planas:
   * - Vazio (cinza #f0f0f1)
   * - Muito fraca (fundo #ffabaf, borda #f86368)
   * - Fraca (fundo #ffe399, borda #ffc733)
   * - Média (fundo #c1e1b9, borda #83c373)
   * - Forte (fundo #83c373, borda #68b04a)
   */
  var passwordStrengthInputs = document.querySelectorAll('[data-password-strength-input], #pass1, #password');
  var strengthMeter = document.getElementById('pass-strength-result');

  function evaluatePasswordStrength(password) {
    if (!password || password.length === 0) {
      return { score: 0, text: '', className: 'strength-empty' };
    }

    var score = 0;

    // Critério 1: Comprimento
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;

    // Critério 2: Minúsculas e Maiúsculas
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;

    // Critério 3: Números
    if (/\d/.test(password)) score++;

    // Critério 4: Símbolos especiais
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~`]/.test(password)) score++;

    if (password.length < 6 || score <= 1) {
      return { score: 1, text: 'Muito fraca', className: 'strength-short' };
    } else if (score === 2) {
      return { score: 2, text: 'Fraca', className: 'strength-bad' };
    } else if (score === 3 || score === 4) {
      return { score: 3, text: 'Média', className: 'strength-good' };
    } else {
      return { score: 4, text: 'Forte', className: 'strength-strong' };
    }
  }

  if (strengthMeter && passwordStrengthInputs.length > 0) {
    passwordStrengthInputs.forEach(function (input) {
      input.addEventListener('input', function () {
        var val = input.value;
        var result = evaluatePasswordStrength(val);

        // Remove classes anteriores
        strengthMeter.className = 'pass-strength-result ' + result.className;
        strengthMeter.textContent = result.text;
      });
    });
  }

  /* ===== 3. SELECÇÃO GERAL DE CHECKBOXES (SELECCIONAR TODOS) ===== */
  var selectAllCheckboxes = document.querySelectorAll('[data-select-all], .select-all-checkbox');

  selectAllCheckboxes.forEach(function (masterCheckbox) {
    masterCheckbox.addEventListener('change', function () {
      var table = masterCheckbox.closest('table') || (masterCheckbox.closest('.box') && masterCheckbox.closest('.box').querySelector('table'));
      if (!table) return;

      var rowCheckboxes = table.querySelectorAll('tbody input[type="checkbox"]');
      rowCheckboxes.forEach(function (cb) {
        cb.checked = masterCheckbox.checked;
      });

      var selectedCount = masterCheckbox.checked ? rowCheckboxes.length : 0;
      updateBatchActions(selectedCount);
    });
  });

  document.addEventListener('change', function (e) {
    if (e.target.matches('tbody input[type="checkbox"]')) {
      var table = e.target.closest('table');
      if (!table) return;

      var rowCheckboxes = table.querySelectorAll('tbody input[type="checkbox"]');
      var checkedCount = 0;
      rowCheckboxes.forEach(function (cb) {
        if (cb.checked) checkedCount++;
      });

      var master = table.querySelector('thead input[type="checkbox"]');
      if (master) {
        master.checked = checkedCount > 0 && checkedCount === rowCheckboxes.length;
      }

      updateBatchActions(checkedCount);
    }
  });

  function updateBatchActions(count) {
    var batchBadge = document.getElementById('selected-batch-count');
    if (batchBadge) {
      batchBadge.textContent = count > 0 ? '(' + count + ')' : '';
    }
  }

  /* ===== 4. EXPANSÃO / RECOLHA DA ÁREA DE FILTROS ===== */
  var filterToggleBtns = document.querySelectorAll('[data-action="toggle-filter"], .btn-toggle-filter');
  filterToggleBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var box = btn.closest('.box') || document;
      var filterBox = box.querySelector('.filter-box');
      if (filterBox) {
        filterBox.classList.toggle('show');
      }
    });
  });

  var filterResetBtns = document.querySelectorAll('[data-action="reset-filter"], .btn-reset-filter');
  filterResetBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var filterBox = btn.closest('.filter-box');
      if (filterBox) {
        var inputs = filterBox.querySelectorAll('input, select');
        inputs.forEach(function (input) {
          if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false;
          } else {
            input.value = '';
          }
        });
        if (typeof window.showToast === 'function') {
          window.showToast('info', 'Filtros limpos. A apresentar todos os registos.');
        }
      }
    });
  });

  /* ===== 5. SISTEMA DE SEPARADORES (TABS EM JS VANILLA) ===== */
  var tabLinks = document.querySelectorAll('.nav-tabs > li > a');

  tabLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      var parentLi = link.parentElement;
      var navTabs = parentLi.parentElement;
      var targetSelector = link.getAttribute('href');

      if (!targetSelector || targetSelector === '#') return;

      var siblings = navTabs.querySelectorAll('li');
      siblings.forEach(function (li) {
        li.classList.remove('active');
      });
      parentLi.classList.add('active');

      var tabContainer = navTabs.nextElementSibling || document.querySelector('.tab-content');
      if (tabContainer) {
        var panes = tabContainer.querySelectorAll('.tab-pane');
        panes.forEach(function (pane) {
          pane.classList.remove('active');
        });
        var targetPane = document.querySelector(targetSelector);
        if (targetPane) {
          targetPane.classList.add('active');
        }
      }
    });
  });

  /* ===== 6. AÇÕES DE IMPRESSÃO E EXPORTAÇÃO ===== */
  var printBtns = document.querySelectorAll('[data-action="print"], .btn-print');
  printBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      window.print();
    });
  });

  var exportBtns = document.querySelectorAll('[data-action="export"]');
  exportBtns.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var format = btn.getAttribute('data-format') || 'CSV';
      if (typeof window.showToast === 'function') {
        window.showToast('success', 'Ficheiro ' + format + ' gerado com sucesso! O download iniciará em breve.');
      }
    });
  });

  /* ===== 7. BOTÃO DE TESTE DE IMPRESSORA TÉRMICA ===== */
  var testPrintBtn = document.getElementById('btn-test-printer');
  if (testPrintBtn) {
    testPrintBtn.addEventListener('click', function (e) {
      e.preventDefault();
      testPrintBtn.disabled = true;
      testPrintBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> A enviar teste...';

      setTimeout(function () {
        testPrintBtn.disabled = false;
        testPrintBtn.innerHTML = '<i class="fa-solid fa-print"></i> Imprimir Página de Teste';
        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Página de teste enviada com sucesso para a impressora térmica.');
        }
      }, 1200);
    });
  }

  /* ===== 8. SUBMISSÃO DE FORMULÁRIOS COM FEEDBACK ===== */
  var ajaxForms = document.querySelectorAll('form[data-ajax="true"]');
  ajaxForms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var submitBtn = form.querySelector('button[type="submit"]');
      var originalHtml = submitBtn ? submitBtn.innerHTML : '';

      if (submitBtn) {
        submitBtn.disabled = true;
      }

      setTimeout(function () {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalHtml;
        }

        var modal = form.closest('.modal');
        if (modal && typeof window.closeModal === 'function') {
          window.closeModal(modal);
        }

        if (typeof window.showToast === 'function') {
          window.showToast('success', 'Dados guardados com sucesso no NetPos!');
        }
      }, 600);
    });
  });
});
