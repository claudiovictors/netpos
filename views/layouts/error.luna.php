<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <!-- ========== INÍCIO: METADADOS ESSENCIAIS ========== -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="robots" content="noindex, nofollow">
  <!-- ========== FIM: METADADOS ESSENCIAIS ========== -->

  <title>@yield('title', 'Erro') - {{ config('app.name', 'NetPos') }}</title>

  <!-- Favicon do NetPos -->
  <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('assets/images/logo-icon.svg') }}">

  <!-- ========== INÍCIO: ESTILOS EXTERNOS (APENAS FONT AWESOME PARA ÍCONES DECORATIVOS) ========== -->
  <!-- Usado apenas para ícones com aria-hidden="true". A página permanece 100% legível e funcional caso a CDN falhe -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
  <!-- ========== FIM: ESTILOS EXTERNOS ========== -->

  <!-- ========== INÍCIO: FOLHA DE ESTILOS PRÓPRIA E INDEPENDENTE ========== -->
  <!-- Folha isolada sem frameworks e sem dependência do style.css ou layouts principais -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/error.css') }}">
  <!-- ========== FIM: FOLHA DE ESTILOS PRÓPRIA E INDEPENDENTE ========== -->

  @yield('styles')
</head>
<body class="error-page">

  <!-- ========== INÍCIO: CONTENTOR PRINCIPAL DO ERRO ========== -->
  <div class="error-container">

    <!-- ========== INÍCIO: LOGO NETPOS ========== -->
    <header class="error-logo">
      <a href="{{ route('dashboard') }}" title="{{ config('app.name', 'NetPos') }}" aria-label="{{ config('app.name', 'NetPos') }}">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="{{ config('app.name', 'NetPos') }}" width="180" height="45">
      </a>
    </header>
    <!-- ========== FIM: LOGO NETPOS ========== -->

    <!-- ========== INÍCIO: CONTEÚDO ESPECÍFICO DO ERRO ========== -->
    <main role="main">
      @yield('content')
    </main>
    <!-- ========== FIM: CONTEÚDO ESPECÍFICO DO ERRO ========== -->

    <!-- ========== INÍCIO: RODAPÉ ========== -->
    <footer class="error-footer">
      <p>&copy; {{ date('Y') }} {{ config('app.name', 'NetPos') }}. Todos os direitos reservados.</p>
    </footer>
    <!-- ========== FIM: RODAPÉ ========== -->

  </div>
  <!-- ========== FIM: CONTENTOR PRINCIPAL DO ERRO ========== -->

  <!-- ========== INÍCIO: SCRIPTS VANILLA INDEPENDENTES ========== -->
  <script src="{{ asset('assets/admin/js/error.js') }}" defer></script>
  @yield('scripts')
  <!-- ========== FIM: SCRIPTS VANILLA INDEPENDENTES ========== -->

</body>
</html>
