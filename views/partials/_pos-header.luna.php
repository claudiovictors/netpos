<header class="pos-topbar" role="banner">
    {{-- Lado Esquerdo: Botão Hambúrguer para alternar vista de outras opções --}}
    <div class="pos-topbar-left">
        <button type="button" class="pos-topbar-toggle" id="pos-topbar-toggle" title="Outras opções e menu de funções"
            aria-label="Abrir outras opções">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    {{-- Centro: Identificação do NetPos --}}
    <div class="pos-topbar-center">
        <div class="pos-topbar-logo-icon">
            <img src="{{ asset('assets/images/logo-icon-white.svg') }}" alt="{{ config('app.name') }}" width="22" height="22">
        </div>
        <h1 class="pos-topbar-title">{{ config('app.name') }}</h1>
        <span class="pos-topbar-badge">Vendas</span>
    </div>

    {{-- Lado Direito: Botão "X" para regressar ao Backoffice / Dashboard --}}
    <div class="pos-topbar-right">
        <a href="#" class="pos-topbar-close" title="Terminar Sessão"
            aria-label="Voltar ao menu principal">
            <i class="fa-solid fa-xmark"></i>
        </a>
    </div>
</header>