<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ecrã de Vendas | NetPos')</title>

    {{-- Tipografia Google Fonts: Source Sans Pro --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600&display=swap" rel="stylesheet">

    {{-- Pacote de Ícones: Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Folha de Estilos Mestra (importa todos os CSS organizados) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- Folha de Estilos Mestra (importa todos os CSS organizados) --}}
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('assets/images/logo-icon.svg') }}">
</head>

<body>

    {{-- INÍCIO: CONTENTOR RAIZ DO ECRÃ POS (100VW x 100VH) --}}
    <div class="pos-screen" id="pos-screen">

        {{-- INÍCIO: BARRA SUPERIOR --}}
        @include('partials._pos-header')
        {{-- FIM: BARRA SUPERIOR --}}

        {{-- INÍCIO: CORPO PRINCIPAL DE TRABALHO --}}
        <main class="pos-main" role="main">
            @yield('content')
        </main>
        {{-- FIM: CORPO PRINCIPAL --}}

        {{-- INÍCIO: BARRA INFERIOR --}}
        <footer class="pos-statusbar" role="contentinfo">
            <!-- Lado Esquerdo: Menção Fiscal e Relógio ao Vivo -->
            <div class="pos-statusbar-left">
                <span class="pos-statusbar-tax-notice">Preços com imposto incluído</span>
                <span class="pos-statusbar-clock" id="pos-live-clock">
                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                    <span>A carregar relógio...</span>
                </span>
            </div>

            <!-- Centro: Informação Operacional e Caixa -->
            <div class="pos-statusbar-center">
                <div class="pos-statusbar-item">
                    <span>N.º Itens:</span>
                    <strong id="pos-status-item-count">3</strong>
                </div>
                <div class="pos-statusbar-item">
                    <span>Posto:</span>
                    <strong>1</strong>
                </div>
                <div class="pos-statusbar-item">
                    <span>Turno:</span>
                    <strong id="pos-status-session-time">Aberto às 08:30</strong>
                </div>
                <div class="pos-statusbar-user" title="Operador atual da caixa">
                    <span class="pos-avatar pos-avatar-user">A</span>
                    <span>Utilizador: <strong>{{ (auth()->user()->name) ? 'Administrador' :
                            'Funcionário'}}</strong></span>
                </div>
            </div>

            <!-- Lado Direito: Periféricos e Estado AGT -->
            <div class="pos-statusbar-right">
                <div class="pos-hardware-icons">
                    <button type="button" class="pos-hardware-btn" title="Impressora térmica 80mm ligada"
                        aria-label="Impressora térmica operacional">
                        <i class="fa-solid fa-print"></i>
                    </button>
                    <button type="button" class="pos-hardware-btn" title="Gaveta de dinheiro ligada à impressora"
                        aria-label="Gaveta de dinheiro operacional">
                        <i class="fa-solid fa-cash-register"></i>
                    </button>
                </div>

                <!-- Chip Interativo da AGT (Clique para alternar o estado de teste) -->
                <div class="pos-agt-badge status-connected" id="pos-agt-badge"
                    title="Clique para testar os estados da AGT (Ligado, Pendente, Offline)">
                    <span class="pos-status-dot"></span>
                    <span id="pos-agt-text">AGT Online</span>
                </div>
            </div>
        </footer>
        {{-- FIM: BARRA INFERIOR --}}
    </div>
    {{-- FIM: CONTENTOR RAIZ DO ECRÃ POS --}}

    {{-- INÍCIO: SECÇÃO DE JANELAS MODAIS --}}
    <div class="pos-modal-backdrop" id="pos-modal-backdrop">
        @yield('models')
    </div>
    {{-- FIM: SECÇÃO DE JANELAS MODAIS --}}

    {{-- INÍCIO: CONTENTOR DE TOASTS --}}
    <div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true"></div>
    {{-- FIM: CONTENTOR DE TOASTS --}}

    {{-- INÍCIO: SCRIPTS GLOBAL VANILLA DO POS --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/model.js') }}"></script>
    <script src="{{ asset('assets/js/products.js') }}"></script>
    <script src="{{ asset('assets/js/customers.js') }}"></script>
    <script src="{{ asset('assets/js/api.js') }}"></script>
    <script src="{{ asset('assets/js/cart.js') }}"></script>
    <script src="{{ asset('assets/js/keypad.js') }}"></script>
    <script src="{{ asset('assets/js/checkout.js') }}"></script>
    <script src="{{ asset('assets/js/receipt.js') }}"></script>
    <script src="{{ asset('assets/js/documents.js') }}"></script>
    <script src="{{ asset('assets/js/format.js') }}"></script>
</body>

</html>