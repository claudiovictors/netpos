<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard | NetPos Admin')</title>

    {!! csrf_meta() !!}

    {{-- Tipografia Google Fonts: Source Sans Pro --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600&display=swap" rel="stylesheet">

    {{-- Pacote de Ícones: Font Awesome 6  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Folha de Estilos Mestra (importa todos os CSS organizados) --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    {{-- Folha de Estilos Mestra (importa todos os CSS organizados) --}}
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('assets/images/logo-icon.svg') }}">
</head>
<body class="hold-transition">

    {{-- INÍCIO: ESTRUTURA GERAL (WRAPPER) --}}
    <div class="wrapper">
        {{-- INÍCIO: HEADER (BARRA SUPERIOR AZUL #3c8dbc) --}}
        @include('partials.header')
        {{-- FIM: HEADER --}}

        {{-- INÍCIO: SIDEBAR (MENU LATERAL CLARO ADMINLTE 2) --}}
        @include('partials.sidebar')
        {{-- FIM: SIDEBAR --}}

        {{-- INÍCIO: CONTEÚDO DA PÁGINA (CONTENT-WRAPPER) --}}
        <div class="content-wrapper">
            @yield('content')
        </div>
        {{-- FIM: CONTEÚDO DA PÁGINA (CONTENT-WRAPPER) --}}

        {{-- INÍCIO: FOOTER (RODAPÉ DA PÁGINA) --}}
        @include('partials.footer')
        {{-- FIM: FOOTER --}}
    </div>

    <div class="models-section">
        @yield('model')
    </div>

    @stack('scripts')

    {{-- Gestão da Barra Lateral (Desktop e Mobile) --}}
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script src="{{ asset('assets/admin/js/toast.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sidebar.js') }}"></script>
    <script src="{{ asset('assets/admin/js/model.js') }}"></script>
</body>
</html>