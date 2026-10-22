@extends('layouts.app')

@section('title')
Relatórios de Vendas | {{ config('app.name') }} Admin
@endsection

@section('content')
{{-- Cabeçalho da Página (Título e Subtítulo --}}
<section class="content-header">
    <h1>
        Relatórios
        <small>Análise detalhada de vendas, lucros e desempenho de caixa</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Início</a></li>
        <li class="active">Relatórios</li>
    </ol>
</section>
{{-- FIM: CABEÇALHO --}}

{{-- Conteúdo Principal --}}
<section class="content">
    {{-- INÍCIO: CARDS (RESUMO DO PERÍODO SELECCIONADO) --}}
    @include('partials.row-reports')
    {{-- FIM: CARDS --}}

    {{-- INÍCIO: FILTROS DE RELATÓRIO --}}
    <div class="box box-primary no-print">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa-solid fa-filter" style="color: #3c8dbc; margin-right: 5px;"></i>
                Parâmetros do Relatório</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-default btn-sm" data-action="print">
                    <i class="fa-solid fa-print"></i> Imprimir Relatório
                </button>
                <button type="button" class="btn btn-info btn-sm" data-action="export" data-format="PDF">
                    <i class="fa-solid fa-file-pdf"></i> Exportar PDF
                </button>
            </div>
        </div>

        <div class="box-body">
            <form id="form-report-filter"
                onsubmit="event.preventDefault(); window.showToast('info', 'Dados do relatório actualizados.');">
                <div class="row" style="display: flex; align-items: center;">
                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Intervalo de Datas</label>
                            <input type="date" name="start_date" class="form-control @error_class('start_date')"
                                value="2026-10-01">
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Até à Data</label>
                            <input type="date" name="end_date" class="form-control @error_class('end_date')"
                                value="2026-10-21">
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Funcionário</label>
                            <select name="employee_id" class="form-control @error_class('employee_id')">
                                <option value="">Todos</option>
                                <option value="1">Carlos Santos</option>
                                <option value="2">Maria Antónia</option>
                                <option value="3">Paulo Jorge</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-group">
                            <label class="control-label">Método Pagamento</label>
                            <select name="payment_method" class="form-control @error_class('payment_method')">
                                <option value="">Todos</option>
                                <option value="multicaixa">Multicaixa</option>
                                <option value="numerario">Numerário</option>
                                <option value="transferencia">Transferência</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- FIM: FILTROS DE RELATÓRIO --}}

    {{-- INÍCIO: GRÁFICOS DO RELATÓRIO --}}
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa-solid fa-chart-line"
                            style="color: #3c8dbc; margin-right: 5px;"></i> Facturação Diária do Mês</h3>
                </div>
                <div class="box-body">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="chart-sales-daily"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa-solid fa-credit-card"
                            style="color: #00a65a; margin-right: 5px;"></i> Métodos de Pagamento</h3>
                </div>
                <div class="box-body">
                    <div style="position: relative; height: 260px; width: 100%;">
                        <canvas id="chart-reports-payments"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa-solid fa-user-check"
                            style="color: #3c8dbc; margin-right: 5px;"></i> Desempenho por Operador de Caixa</h3>
                </div>
                <div class="box-body">
                    <div style="position: relative; height: 240px; width: 100%;">
                        <canvas id="chart-sales-employee"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa-solid fa-chart-pie"
                            style="color: #55acee; margin-right: 5px;"></i> Participação por Categoria</h3>
                </div>
                <div class="box-body">
                    <div style="position: relative; height: 240px; width: 100%;">
                        <canvas id="chart-reports-categories"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- FIM: GRÁFICOS DO RELATÓRIO --}}

    {{-- INÍCIO: TABELA (RELATÓRIO DETALHADO DE VENDAS) --}}
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa-solid fa-table-list" style="color: #3c8dbc; margin-right: 5px;"></i>
                Registo Detalhado de Vendas</h3>
            <div class="box-tools no-print">
                <button type="button" class="btn btn-default btn-xs" data-action="export" data-format="CSV">
                    <i class="fa-solid fa-download"></i> CSV
                </button>
            </div>
        </div>

        <div class="box-body no-padding">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nº Factura</th>
                            <th>Data e Hora</th>
                            <th>Cliente</th>
                            <th>Operador de Caixa</th>
                            <th>Método Pagamento</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-right">IVA (14%)</th>
                            <th class="text-right">Total Factura</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="label label-info">FT 2026/0089</span></td>
                            <td>21/10/2026 15:42</td>
                            <td>João Manuel da Silva</td>
                            <td>Carlos Santos</td>
                            <td><span class="label label-primary">Multicaixa</span></td>
                            <td class="text-right">39.649,12 Kz</td>
                            <td class="text-right">5.550,88 Kz</td>
                            <td class="text-right"><strong>45.200,00 Kz</strong></td>
                            <td class="text-center"><span class="label label-success">Concluída</span></td>
                        </tr>
                        <tr>
                            <td><span class="label label-info">FT 2026/0088</span></td>
                            <td>21/10/2026 15:30</td>
                            <td>Consumidor Final</td>
                            <td>Maria Antónia</td>
                            <td><span class="label label-default">Numerário</span></td>
                            <td class="text-right">10.964,91 Kz</td>
                            <td class="text-right">1.535,09 Kz</td>
                            <td class="text-right"><strong>12.500,00 Kz</strong></td>
                            <td class="text-center"><span class="label label-success">Concluída</span></td>
                        </tr>
                        <tr>
                            <td><span class="label label-info">FT 2026/0087</span></td>
                            <td>21/10/2026 14:15</td>
                            <td>Empresa Delta, Lda</td>
                            <td>Carlos Santos</td>
                            <td><span class="label label-warning">Transferência</span></td>
                            <td class="text-right">161.403,51 Kz</td>
                            <td class="text-right">22.596,49 Kz</td>
                            <td class="text-right"><strong>184.000,00 Kz</strong></td>
                            <td class="text-center"><span class="label label-success">Concluída</span></td>
                        </tr>
                        <tr>
                            <td><span class="label label-info">FT 2026/0086</span></td>
                            <td>21/10/2026 13:50</td>
                            <td>Beatriz Fernandes</td>
                            <td>Maria Antónia</td>
                            <td><span class="label label-primary">Multicaixa</span></td>
                            <td class="text-right">5.921,05 Kz</td>
                            <td class="text-right">828,95 Kz</td>
                            <td class="text-right"><strong>6.750,00 Kz</strong></td>
                            <td class="text-center"><span class="label label-success">Concluída</span></td>
                        </tr>
                        <tr>
                            <td><span class="label label-info">FT 2026/0085</span></td>
                            <td>21/10/2026 12:20</td>
                            <td>António Ferreira</td>
                            <td>Carlos Santos</td>
                            <td><span class="label label-primary">Multicaixa</span></td>
                            <td class="text-right">26.228,07 Kz</td>
                            <td class="text-right">3.671,93 Kz</td>
                            <td class="text-right"><strong>29.900,00 Kz</strong></td>
                            <td class="text-center"><span class="label label-success">Concluída</span></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f9f9f9; font-weight: bold;">
                            <td colspan="5" class="text-right">TOTAIS DO RELATÓRIO:</td>
                            <td class="text-right">244.166,66 Kz</td>
                            <td class="text-right">34.183,34 Kz</td>
                            <td class="text-right" style="color: #00a65a; font-size: 14px;">278.350,00 Kz</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="table-pagination-footer no-print">
            <div class="pagination-info">
                A mostrar de <strong>1</strong> a <strong>5</strong>, total de <strong>412</strong> vendas no período
            </div>
            <div class="pagination-controls">
                <ul class="pagination">
                    <li class="disabled"><span>&laquo;</span></li>
                    <li class="active"><span>1</span></li>
                    <li><a href="#"
                            onclick="event.preventDefault(); window.showToast('info', 'A carregar mais vendas...');">2</a>
                    </li>
                    <li><a href="#"
                            onclick="event.preventDefault(); window.showToast('info', 'A carregar mais vendas...');">3</a>
                    </li>
                    <li><a href="#"
                            onclick="event.preventDefault(); window.showToast('info', 'A carregar mais vendas...');">&raquo;</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- FIM: TABELA --}}
</section>
@endsection