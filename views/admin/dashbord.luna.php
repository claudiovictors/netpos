@extends('layouts.app')

@section('title')
Dashboard | {{ config('app.name') }} Admin
@endsection

@section('content')
{{-- Cabeçalho da Página (Título e Subtítulo --}}
<section class="content-header">
  <h1>
    Dashboard
    <small>Painel de controlo e visão geral do sistema</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Início</a></li>
    <li class="active">Dashboard</li>
  </ol>
</section>
{{-- FIM: CABEÇALHO --}}

{{-- Conteúdo Principal --}}
<section class="content">
  {{-- INÍCIO: CARDS (SMALL-BOX DE RESUMO E KPIS) --}}
  @include('partials.small-box')
  {{-- FIM: CARDS (SMALL-BOX DE RESUMO E KPIS) --}}

  {{-- INÍCIO: SECÇÃO DE GRÁFICOS (ESTRUTURA BASE PARA FASE 3) --}}
  <div class="row">

    <!-- Gráfico Principal: Vendas e Lucros dos Últimos 12 Meses -->
    <div class="col-12 col-md-8">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa-solid fa-chart-area" style="color: #3c8dbc; margin-right: 6px;"></i>
            Evolução de Vendas e Lucros (Últimos 12 Meses)
          </h3>
          <div class="box-tools">
            <span class="label label-primary">Anual</span>
            <button type="button" class="btn btn-default btn-xs" title="Actualizar"
              onclick="window.showToast('info', 'Dados de vendas actualizados.');">
              <i class="fa-solid fa-rotate"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <!-- Contentor preparado para Chart.js da Fase 3 -->
          <div class="chart-container"
            style="position: relative; height: 300px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #fafafa; border: 1px dashed #d2d6de; border-radius: 3px;">
            <div id="chart-sales-profit-placeholder" style="text-align: center; color: #888;">
              <i class="fa-solid fa-chart-line"
                style="font-size: 38px; color: #3c8dbc; margin-bottom: 8px; display: block;"></i>
              <span>Área reservada para o gráfico de Vendas e Lucros</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráfico Secundário: Vendas por Categoria (Doughnut) -->
    <div class="col-12 col-md-4">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa-solid fa-chart-pie" style="color: #55acee; margin-right: 6px;"></i>
            Vendas por Categoria
          </h3>
          <div class="box-tools">
            <button type="button" class="btn btn-default btn-xs"
              onclick="window.showToast('info', 'Distribuição por categoria actualizada.');">
              <i class="fa-solid fa-rotate"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <!-- Contentor preparado para Chart.js da Fase 3 -->
          <div class="chart-container"
            style="position: relative; height: 300px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #fafafa; border: 1px dashed #d2d6de; border-radius: 3px;">
            <div id="chart-categories-placeholder" style="text-align: center; color: #888;">
              <i class="fa-solid fa-pie-chart"
                style="font-size: 38px; color: #55acee; margin-bottom: 8px; display: block;"></i>
              <span>Área reservada para o gráfico circular por categoria</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="row">

    <!-- Gráfico de Barras: Produtos Mais Vendidos -->
    <div class="col-12 col-md-6">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa-solid fa-ranking-star" style="color: #00a65a; margin-right: 6px;"></i>
            Top 5 Produtos Mais Vendidos
          </h3>
        </div>
        <div class="box-body">
          <!-- Contentor preparado para Chart.js da Fase 3 -->
          <div class="chart-container"
            style="position: relative; height: 260px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #fafafa; border: 1px dashed #d2d6de; border-radius: 3px;">
            <div id="chart-top-products-placeholder" style="text-align: center; color: #888;">
              <i class="fa-solid fa-chart-column"
                style="font-size: 38px; color: #00a65a; margin-bottom: 8px; display: block;"></i>
              <span>Área reservada para o ranking de produtos</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gráfico de Barras: Stock Actual vs Stock Mínimo -->
    <div class="col-12 col-md-6">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa-solid fa-warehouse" style="color: #f39c12; margin-right: 6px;"></i>
            Comparativo de Stock: Actual vs. Mínimo
          </h3>
        </div>
        <div class="box-body">
          <!-- Contentor preparado para Chart.js da Fase 3 -->
          <div class="chart-container"
            style="position: relative; height: 260px; width: 100%; display: flex; align-items: center; justify-content: center; background-color: #fafafa; border: 1px dashed #d2d6de; border-radius: 3px;">
            <div id="chart-stock-placeholder" style="text-align: center; color: #888;">
              <i class="fa-solid fa-chart-bar"
                style="font-size: 38px; color: #f39c12; margin-bottom: 8px; display: block;"></i>
              <span>Área reservada para o comparativo de stock</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
  {{-- FIM: SECÇÃO DE GRÁFICOS --}}

  {{-- INÍCIO: TABELA (ÚLTIMAS VENDAS E ALERTAS DE STOCK) --}}
  <div class="row">

    <!-- Coluna 1: Tabela Últimas Vendas -->
    <div class="col-12 col-md-8">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa-solid fa-receipt" style="color: #3c8dbc; margin-right: 6px;"></i>
            Últimas Vendas Realizadas
          </h3>
          <div class="box-tools">
            <button type="button" class="btn btn-primary btn-xs"
              onclick="window.showToast('success', 'Lista de vendas actualizada com sucesso.');">
              <i class="fa-solid fa-rotate"></i> Actualizar
            </button>
            <a href="{{ route('reports') }}" class="btn btn-default btn-xs">
              Ver todas as vendas
            </a>
          </div>
        </div>

        <div class="box-body no-padding">
          <div class="table-responsive">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th>Nº Factura</th>
                  <th>Cliente</th>
                  <th>Funcionário</th>
                  <th>Total</th>
                  <th>Pagamento</th>
                  <th>Estado</th>
                  <th>Data</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="label label-info">FT 2026/0089</span></td>
                  <td>João Manuel da Silva</td>
                  <td>Carlos Santos</td>
                  <td><strong>45.200,00 Kz</strong></td>
                  <td><span class="label label-default">Multicaixa</span></td>
                  <td><span class="label label-success">Concluída</span></td>
                  <td>Hoje 15:42</td>
                </tr>
                <tr>
                  <td><span class="label label-info">FT 2026/0088</span></td>
                  <td>Consumidor Final</td>
                  <td>Maria Antónia</td>
                  <td><strong>12.500,00 Kz</strong></td>
                  <td><span class="label label-default">Numerário</span></td>
                  <td><span class="label label-success">Concluída</span></td>
                  <td>Hoje 15:30</td>
                </tr>
                <tr>
                  <td><span class="label label-info">FT 2026/0087</span></td>
                  <td>Empresa Delta, Lda</td>
                  <td>Carlos Santos</td>
                  <td><strong>184.000,00 Kz</strong></td>
                  <td><span class="label label-default">Transferência</span></td>
                  <td><span class="label label-success">Concluída</span></td>
                  <td>Hoje 14:15</td>
                </tr>
                <tr>
                  <td><span class="label label-info">FT 2026/0086</span></td>
                  <td>Beatriz Fernandes</td>
                  <td>Maria Antónia</td>
                  <td><strong>6.750,00 Kz</strong></td>
                  <td><span class="label label-default">Multicaixa</span></td>
                  <td><span class="label label-success">Concluída</span></td>
                  <td>Hoje 13:50</td>
                </tr>
                <tr>
                  <td><span class="label label-info">FT 2026/0085</span></td>
                  <td>António Ferreira</td>
                  <td>Carlos Santos</td>
                  <td><strong>29.900,00 Kz</strong></td>
                  <td><span class="label label-default">Multicaixa</span></td>
                  <td><span class="label label-success">Concluída</span></td>
                  <td>Hoje 12:20</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="box-footer clearfix" style="text-align: right;">
          <a href="{{ route('reports') }}" class="btn btn-default btn-sm">
            Abrir Relatório Completo de Facturação &rarr;
          </a>
        </div>
      </div>
    </div>

    {{-- Coluna 2: Lista de Produtos em Stock Crítico --}}
    <div class="col-12 col-md-4">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">
            <i class="fa-solid fa-triangle-exclamation" style="color: #dd4b39; margin-right: 6px;"></i>
            Produtos em Stock Baixo
          </h3>
          <div class="box-tools">
            <span class="label label-danger">{{ $totalProductCrit ?? 0 }} Críticos</span>
          </div>
        </div>

        <div class="box-body no-padding">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Produto</th>
                  <th class="text-center">Actual</th>
                  <th class="text-center">Mínimo</th>
                  <th class="text-right">Acção</th>
                </tr>
              </thead>
              <tbody>
                @forelse($criticalProducts ?? [] as $product)
                <tr>
                  <td>
                    <strong>{{ $product->name }}</strong>
                    <div style="font-size: 11px; color: #888;">
                      {{ $product->category ? $product->category->name : 'Sem Categoria' }}
                    </div>
                  </td>
                  <td class="text-center">
                    <span class="stock-warning-text">{{ $product->stock }} un</span>
                  </td>
                  <td class="text-center">{{ $product->min_stock }} un</td>
                  <td class="text-right">
                    <a href="{{ route('product') }}" class="btn btn-danger btn-xs" title="Gerir Stock">
                      <i class="fa-solid fa-plus"></i>
                    </a>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="4" class="text-center text-muted" style="padding: 20px;">
                    Nenhum produto em stock crítico.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="box-footer text-center">
          <a href="{{ route('product') }}?stock_level=low" class="btn btn-default btn-sm btn-block">
            Ver Todos os Produtos em Risco
          </a>
        </div>
      </div>
    </div>

  </div>
  {{-- FIM: TABELA (ÚLTIMAS VENDAS E ALERTAS DE STOCK) --}}
</section>
@endsection