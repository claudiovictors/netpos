{{-- ========================================================================== --}}
{{-- NETPOS - PÁGINA DE ERRO 404 (PÁGINA NÃO ENCONTRADA)                       --}}
{{-- Template Luna (sintaxe idêntica ao Blade)                                  --}}
{{-- Estende exclusivamente layouts.error                                       --}}
{{-- ========================================================================== --}}

@extends('layouts.error')

@section('title', '404 - Página não encontrada')

@section('content')
<!-- ========== INÍCIO: CARTÃO DO ERRO 404 ========== -->
<article class="error-card error-404">

  <!-- ========== INÍCIO: ÍCONE DECORATIVO ========== -->
  <div class="error-icon" aria-hidden="true">
    <i class="fa-solid fa-compass"></i>
  </div>
  <!-- ========== FIM: ÍCONE DECORATIVO ========== -->

  <!-- ========== INÍCIO: CÓDIGO DO ERRO ========== -->
  <div class="error-code" aria-hidden="true">
    {{ $code ?? 404 }}
  </div>
  <!-- ========== FIM: CÓDIGO DO ERRO ========== -->

  <!-- ========== INÍCIO: TÍTULO DO ERRO ========== -->
  <h1 class="error-title">Página não encontrada</h1>
  <!-- ========== FIM: TÍTULO DO ERRO ========== -->

  <!-- ========== INÍCIO: MENSAGEM DO ERRO ========== -->
  <p class="error-message">
    {{ $message ?? 'O endereço solicitado não foi encontrado no sistema ou foi movido. Verifique o URL introduzido ou regresse à página inicial do NetPos.' }}
  </p>
  <!-- ========== FIM: MENSAGEM DO ERRO ========== -->

  <!-- ========== INÍCIO: BOTÕES DE ACÇÃO ========== -->
  <nav class="error-actions" aria-label="Ações de recuperação">
    <!-- Botão Secundário: Voltar atrás através do histórico de navegação -->
    <button type="button" class="btn btn-secondary" data-action="back" data-fallback-url="{{ route('dashboard') }}">
      <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
      <span>Voltar atrás</span>
    </button>

    <!-- Botão Primário: Regressar ao dashboard / início -->
    <a href="{{ route('pos') }}" class="btn btn-primary">
      <i class="fa-solid fa-house" aria-hidden="true"></i>
      <span>Ir para o início</span>
    </a>
  </nav>
  <!-- ========== FIM: BOTÕES DE ACÇÃO ========== -->

</article>
<!-- ========== FIM: CARTÃO DO ERRO 404 ========== -->
@endsection
