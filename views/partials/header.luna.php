<header class="main-header">

    <!-- ========== INÍCIO: LOGO NETPOS NO HEADER ========== -->
    <a href="{{ route('dashboard') }}" class="logo" title="{{ config('app.name') }}">
        <!-- Versão Mini (para quando a sidebar está colapsada) -->
        <span class="logo-mini">
            <img src="{{ asset('assets/images/logo-icon-white.svg') }}" alt="{{ config('app.name') }}">
        </span>

        <!-- Versão Completa (expandida) -->
        <span class="logo-lg">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="{{ config('app.name') }}">
        </span>
    </a>
    <!-- ========== FIM: LOGO NETPOS NO HEADER ========== -->

    <!-- Barra de Navegação Superior -->
    <nav class="navbar">

        <!-- Botão de Alternância do Menu (Hambúrguer) -->
        <button type="button" class="sidebar-toggle" data-toggle="offcanvas" role="button"
            aria-label="Alternar navegação">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Menu Superior Direito -->
        <div class="navbar-custom-menu">
            <ul class="navbar-nav">

                <!-- Ícone de Notificações / Mensagens -->
                <li class="messages-menu">
                    <a href="#" title="Mensagens e Notificações"
                        onclick="event.preventDefault(); window.showToast('info', 'Não existem novas mensagens pendentes.');">
                        <i class="fa-regular fa-envelope"></i>
                    </a>
                </li>

                <!-- Perfil do Utilizador com Menu Dropdown -->
                <li class="user-menu dropdown" data-dropdown-container>
                    <a href="#" class="dropdown-toggle" data-dropdown-toggle aria-expanded="false"
                        title="Menu de Utilizador">
                        <span></span>
                        <div class="user-avatar-circle">
                            {!! avatar(auth()->user()->name, 24, '#dd4b39') !!}
                        </div>
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="caret"></span>
                    </a>

                    <!-- Menu Suspenso Limpo em Lista (sem cartão de avatar grande) -->
                    <div class="dropdown-menu dropdown-menu-right">

                        <!-- Lista de opções com ícones planos -->
                        <ul class="dropdown-nav">
                            <li>
                                <a href="{{ route('settings') }}" class="dropdown-item">
                                    <i class="fa-solid fa-gear" aria-hidden="true"></i>
                                    <span>Configurações</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports') }}" class="dropdown-item">
                                    <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                                    <span>Relatórios de Vendas</span>
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"> <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Terminar sessão</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>

    </nav>
</header>