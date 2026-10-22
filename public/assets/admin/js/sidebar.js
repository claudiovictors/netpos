/**
 * ==========================================================================
 * NETPOS - SIDEBAR.JS
 * Controlo da barra lateral de navegação (AdminLTE 2 / laravel-admin style)
 * - Colapsar/expandir no desktop (com persistência em localStorage)
 * - Abrir/fechar no ecrã móvel com overlay escurecido
 * - Expansão e recolha de submenus em árvore (.treeview) com rotação da seta
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var body = document.body;
  var sidebarToggleBtns = document.querySelectorAll('[data-toggle="offcanvas"], .sidebar-toggle');
  var treeviewLinks = document.querySelectorAll('.sidebar-menu .treeview > a');
  var overlay = document.querySelector('.sidebar-overlay');

  // Chave de armazenamento local para persistir o estado do sidebar
  var STORAGE_KEY = 'netpos_sidebar_collapsed';

  /* ===== 1. RESTAURAR ESTADO SALVO NO DESKTOP ===== */
  var isCollapsed = localStorage.getItem(STORAGE_KEY) === 'true';
  if (isCollapsed && window.innerWidth >= 768) {
    body.classList.add('sidebar-collapse');
  }

  /* ===== 2. TOGGLE DO SIDEBAR (DESKTOP E MOBILE) ===== */
  function toggleSidebar(e) {
    if (e) {
      e.preventDefault();
    }

    if (window.innerWidth >= 768) {
      // Modo Desktop: alterna classe .sidebar-collapse e guarda preferência
      body.classList.toggle('sidebar-collapse');
      var currentState = body.classList.contains('sidebar-collapse');
      localStorage.setItem(STORAGE_KEY, currentState ? 'true' : 'false');
    } else {
      // Modo Mobile: alterna classe .sidebar-open (revela sidebar com overlay)
      body.classList.toggle('sidebar-open');
    }
  }

  // Vincular clique aos botões de alternância da sidebar
  sidebarToggleBtns.forEach(function (btn) {
    btn.addEventListener('click', toggleSidebar);
  });

  // Fechar sidebar ao clicar no overlay em dispositivos móveis
  if (overlay) {
    overlay.addEventListener('click', function () {
      body.classList.remove('sidebar-open');
    });
  }

  /* ===== 3. NAVEGAÇÃO EM ÁRVORE (SUBMENUS COM SETA) ===== */
  treeviewLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
      var parentLi = link.parentElement;
      var submenu = parentLi.querySelector('.treeview-menu');

      if (submenu) {
        e.preventDefault();

        // Se já está aberto, fecha
        if (parentLi.classList.contains('menu-open')) {
          parentLi.classList.remove('menu-open');
          submenu.style.display = 'none';
        } else {
          // Fecha irmãos abertos no mesmo nível para efeito acordeão limpo
          var siblingLis = parentLi.parentElement.querySelectorAll(':scope > .treeview.menu-open');
          siblingLis.forEach(function (sibling) {
            sibling.classList.remove('menu-open');
            var siblingMenu = sibling.querySelector('.treeview-menu');
            if (siblingMenu) {
              siblingMenu.style.display = 'none';
            }
          });

          // Abre o menu clicado
          parentLi.classList.add('menu-open');
          submenu.style.display = 'block';
        }
      }
    });
  });

  /* ===== 4. RESPONSIVIDADE AO REDIMENSIONAR O ECRÃ ===== */
  window.addEventListener('resize', function () {
    if (window.innerWidth >= 768) {
      // Remove classe mobile se a janela foi redimensionada para desktop
      body.classList.remove('sidebar-open');
      if (localStorage.getItem(STORAGE_KEY) === 'true') {
        body.classList.add('sidebar-collapse');
      }
    }
  });
});
