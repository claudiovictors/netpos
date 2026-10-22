{{-- ========================================================================== --}}
{{-- NETPOS - PÁGINA DE ERRO 500 (ERRO INTERNO DO SERVIDOR)                     --}}
{{-- Template Luna (sintaxe idêntica ao Blade)                                  --}}
{{-- Estende exclusivamente layouts.error                                       --}}
{{-- ========================================================================== --}}

@extends('layouts.error')

@section('title', '500 - Erro interno do servidor')

@section('content')
<!-- ========== INÍCIO: CARTÃO DO ERRO 500 ========== -->
<article class="error-card error-500">

  <!-- ========== INÍCIO: ÍCONE DECORATIVO ========== -->
  <div class="error-icon" aria-hidden="true">
    <i class="fa-solid fa-triangle-exclamation"></i>
  </div>
  <!-- ========== FIM: ÍCONE DECORATIVO ========== -->

  <!-- ========== INÍCIO: CÓDIGO DO ERRO ========== -->
  <div class="error-code" aria-hidden="true">
    {{ $code ?? 500 }}
  </div>
  <!-- ========== FIM: CÓDIGO DO ERRO ========== -->

  <!-- ========== INÍCIO: TÍTULO DO ERRO ========== -->
  <h1 class="error-title">Erro interno do servidor</h1>
  <!-- ========== FIM: TÍTULO DO ERRO ========== -->

  <!-- ========== INÍCIO: MENSAGEM DO ERRO ========== -->
  <p class="error-message">
    {{ $message ?? 'Ocorreu uma anomalia imprevista ao processar o seu pedido. A equipa de suporte foi notificada para restabelecer o funcionamento com a máxima brevidade.' }}
  </p>
  <!-- ========== FIM: MENSAGEM DO ERRO ========== -->

  <!-- ========== INÍCIO: CÓDIGO DE REFERÊNCIA DO ERRO ========== -->
  {{-- Disponível para o utilizador reportar ao suporte técnico sem expor dados internos --}}
  @if(!empty($errorId))
    <p class="error-reference">
      Código de referência: <code>{{ $errorId }}</code>
    </p>
  @endif
  <!-- ========== FIM: CÓDIGO DE REFERÊNCIA DO ERRO ========== -->

  <!-- ========== INÍCIO: DETALHES TÉCNICOS (APENAS EM AMBIENTE DE DESENVOLVIMENTO) ========== -->
  {{-- NUNCA exposto em produção. Apenas visível se app.debug estiver activo no ficheiro .env --}}
  @if(config('app.debug') && !empty($debugMessage))
    <details class="error-debug-details">
      <summary>Detalhes técnicos para diagnóstico</summary>
      <pre>{{ $debugMessage }}</pre>
    </details>
  @endif
  <!-- ========== FIM: DETALHES TÉCNICOS ========== -->

  <!-- ========== INÍCIO: BOTÕES DE ACÇÃO ========== -->
  <nav class="error-actions" aria-label="Ações de recuperação">
    <!-- Botão Secundário: Tentar novamente / Recarregar a página -->
    <button type="button" class="btn btn-secondary" data-action="reload">
      <i class="fa-solid fa-rotate-right" aria-hidden="true"></i>
      <span>Tentar novamente</span>
    </button>

    <!-- Botão Primário: Regressar à página inicial -->
    <a href="{{ route('pos') }}" class="btn btn-primary">
      <i class="fa-solid fa-house" aria-hidden="true"></i>
      <span>Ir para o início</span>
    </a>
  </nav>
  <!-- ========== FIM: BOTÕES DE ACÇÃO ========== -->

</article>
<!-- ========== FIM: CARTÃO DO ERRO 500 ========== -->
@endsection
