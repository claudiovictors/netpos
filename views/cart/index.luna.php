@extends('layouts.main')

@section('title')
    Produtos | {{ config('app.name') }} Admin
@endsection

@section('content')
    {{-- INÍCIO: ZONA ESQUERDA (RESUMO, METADADOS E TABELA) --}}
    @include('partials._workspace')
    {{-- FIM: ZONA ESQUERDA --}}


    {{-- INÍCIO: ZONA DIREITA (PAINEL DE ACÇÕES / TILES) --}}
    @include('partials._actions-panel')
    {{-- FIM: ZONA DIREITA --}}
@endsection

@section('models')
    {{-- MODAL 1: ABERTURA DE TURNO (BLOQUEANTE) --}}
    @include('partials.models._modal-open')

    {{-- MODAL 2: FECHO DE TURNO / FECHO DO DIA --}}
    @include('partials.models._modal-close')

    {{-- MODAL 3: ECRÃ DE BLOQUEIO (LOCK SCREEN) --}}
    @include('partials.models._modal-lock')

    {{-- Modal: Pesquisar Produto --}}
    @include('partials.models._modal-search')

    {{-- Modal: Cliente --}}
    @include('partials.models._modal-customer')

    {{-- Modal: Quantidade --}}
    @include('partials.models._modal-quantity')

    {{-- MODAL 7: ALTERAR PREÇO UNITÁRIO --}}
    @include('partials.models._modal-price')

    {{-- MODAL 9: AUTORIZAÇÃO POR PIN (GERENTE) --}}
    @include('partials.models._modal-pin')

    {{-- MODAL 10: CHECKOUT (FINALIZAR VENDA) --}}
    @include('partials.models._modal-checkout')

    {{-- MODAL 11: TALÃO DE VENDA TÉRMICO (RECEIPT) --}}
    @include('partials.models._modal-receipt')

    {{-- MODAL 12: DOCUMENTOS SUSPENSOS --}}
    @include('partials.models._modal-parked')

    {{-- MODAL 13: INFORMAÇÃO DO DOCUMENTO & ANULAÇÃO --}}
    @include('partials.models._modal-doc-info')

    {{-- MODAL 14: CONVERSÃO DE DOCUMENTOS (IMAGEM 2) --}}
    @include('partials.models._modal-convert')

    {{-- MODAL 15: DEVOLUÇÃO / NOTA DE CRÉDITO --}}
    @include('partials.models._modal-return')

    {{-- MODAL 16: ANULAR VENDA EM CURSO (DESCARTAR) --}}
    @include('partials.models._modal-discard')

    {{-- Modal: Teclas de Atalho--}}
    @include('partials.models._modal-shortcuts')
@endsection