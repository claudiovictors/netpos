<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Painel Rápido de Utilizador -->
        <div class="sidebar-user-panel">
            <div class="image">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="info">
                <p>{{ auth()->user()->name ?? 'Administrador' }}</p>
                <small><span class="status-indicator"></span> Online</small>
            </div>
        </div>

        <!-- Cabeçalho de Secção -->
        <div class="sidebar-heading">MENU PRINCIPAL</div>

        <!-- Lista de Navegação -->
        <ul class="sidebar-menu">

            <!-- Item: Dashboard (Activo) -->
            <li class="{{ is_active('/dashboard') }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Item: Produtos -->
            <li class="{{ is_active('/products') }}">
                <a href="{{ route('product') }}">
                    <i class="fa-solid fa-box-archive nav-icon"></i>
                    <span class="nav-text">Produtos</span>
                </a>
            </li>

            <!-- Item: Clientes -->
            <li class="{{ is_active('/customers') }}">
                <a href="{{ route('customers') }}">
                    <i class="fa-solid fa-address-book nav-icon"></i>
                    <span class="nav-text">Clientes</span>
                </a>
            </li>

            <!-- Item: Funcionários -->
            <li class="{{ is_active('/employees') }}">
                <a href="{{ route('employees') }}">
                    <i class="fa-solid fa-users-gear nav-icon"></i>
                    <span class="nav-text">Funcionários</span>
                </a>
            </li>

            <div class="sidebar-heading">VENDAS FUNCIONÁRIOS</div>
            <!-- Item: Funcionários -->
            <li class="{{ is_active('/pos') }}">
                <a href="{{ route('pos') }}">
                    <i class="fa-solid fa-cash-register nav-icon"></i>
                    <span class="nav-text">Ponto de Venda</span>
                </a>
            </li>

            <!-- Cabeçalho de Secção -->
            <div class="sidebar-heading">RELATÓRIOS E DEFINIÇÕES</div>

            <!-- Item: Relatórios -->
            <li class="{{ is_active('/reports') }}">
                <a href="{{ route('reports') }}">
                    <i class="fa-solid fa-chart-pie nav-icon"></i>
                    <span class="nav-text">Relatórios</span>
                </a>
            </li>

            <!-- Item: Configurações do Sistema -->
            <li class="{{ is_active('/settings') }}">
                <a href="{{ route('settings') }}">
                    <i class="fa-solid fa-gear nav-icon"></i>
                    <span class="nav-text">Configurações</span>
                </a>
            </li>

        </ul>

    </section>
</aside>

<!-- Overlay escuro para dispositivos móveis -->
<div class="sidebar-overlay"></div>