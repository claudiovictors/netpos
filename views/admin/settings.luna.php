@extends('layouts.app')

@section('title')
Configurações do Sistema | {{ config('app.name') }} Admin
@endsection

@section('content')
{{-- Cabeçalho da Página (Título e Subtítulo --}}
<section class="content-header">
    <h1>
        Configurações
        <small>Definições da empresa, impressoras térmicas, AGT e sistema</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Início</a></li>
        <li class="active">Configurações</li>
    </ol>
</section>
{{-- FIM: CABEÇALHO --}}

{{-- Conteúdo Principal --}}
<section class="content">
    {{-- INÍCIO: CARDS (BOX COM SEPARADORES TABS) --}}
    <div class="box box-primary">
        <div class="box-body" style="padding-bottom: 0;">

            <!-- Separadores de Navegação (Tabs em JS Vanilla) -->
            <ul class="nav-tabs" role="tablist">
                <li class="active">
                    <a href="#tab-company" role="tab">
                        <i class="fa-solid fa-building"></i> Dados da Empresa
                    </a>
                </li>
                <li>
                    <a href="#tab-printer" role="tab">
                        <i class="fa-solid fa-print"></i> Impressora Térmica
                    </a>
                </li>
                <li>
                    <a href="#tab-agt" role="tab">
                        <i class="fa-solid fa-file-invoice-dollar"></i> AGT / Facturação
                    </a>
                </li>
                <li>
                    <a href="#tab-system" role="tab">
                        <i class="fa-solid fa-sliders"></i> Sistema e Moeda
                    </a>
                </li>
            </ul>

            <!-- Conteúdo dos Separadores -->
            <div class="tab-content">

                <!-- ===== ABA 1: EMPRESA ===== -->
                <div class="tab-pane active" id="tab-company">
                    <form action="#" method="POST" data-ajax="true" novalidate>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Nome Comercial / Razão Social *</label>
                                    <input type="text" name="company_name"
                                        class="form-control @error_class('company_name')"
                                        value="Empresa Exemplo Comércio Geral, Lda" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">NIF da Empresa *</label>
                                    <input type="text" name="company_nif"
                                        class="form-control @error_class('company_nif')" value="5418290381" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-8">
                                <div class="form-group">
                                    <label class="control-label">Endereço da Sede *</label>
                                    <input type="text" name="company_address"
                                        class="form-control @error_class('company_address')"
                                        value="Av. 4 de Fevereiro, Edifício Luanda Tower, Luanda - Angola" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Telefone Principal</label>
                                    <input type="tel" name="company_phone"
                                        class="form-control @error_class('company_phone')" value="(+244) 923 000 000">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email Institucional</label>
                                    <input type="email" name="company_email"
                                        class="form-control @error_class('company_email')"
                                        value="contacto@empresa.co.ao">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Moeda Padrão</label>
                                    <select name="company_currency"
                                        class="form-control @error_class('company_currency')">
                                        <option value="AOA" selected>Kwanza Angolano (Kz / AOA)</option>
                                        <option value="EUR">Euro (€ / EUR)</option>
                                        <option value="USD">Dólar Americano ($ / USD)</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">Logótipo da Empresa (para faturas e recibos)</label>
                                    <input type="file" name="company_logo"
                                        class="form-control @error_class('company_logo')">
                                    <span class="help-block" style="font-size: 11px; color: #888; padding-top: 20px;">Formatos suportados:
                                        PNG, JPG ou SVG plano (máx. 2MB).</span>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer" style="padding-left: 0; padding-right: 0;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Dados da Empresa
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ===== ABA 2: IMPRESSORA TÉRMICA ===== -->
                <div class="tab-pane" id="tab-printer">
                    <form action="#" method="POST" data-ajax="true" novalidate>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Ligação</label>
                                    <select name="printer_type" class="form-control @error_class('printer_type')">
                                        <option value="usb" selected>USB Directo</option>
                                        <option value="network">Rede Ethernet / Wi-Fi (TCP/IP)</option>
                                        <option value="bluetooth">Bluetooth</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Nome da Impressora / IP de Rede</label>
                                    <input type="text" name="printer_target"
                                        class="form-control @error_class('printer_target')"
                                        value="POS-80-Series (192.168.1.200)">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Porta de Rede</label>
                                    <input type="number" name="printer_port"
                                        class="form-control @error_class('printer_port')" value="9100">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Largura do Papel Térmico</label>
                                    <select name="printer_paper_width"
                                        class="form-control @error_class('printer_paper_width')">
                                        <option value="80" selected>80mm (Padrão POS)</option>
                                        <option value="58">58mm (Bobina Compacta)</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Nº de Cópias por Venda</label>
                                    <input type="number" min="1" max="5" name="printer_copies"
                                        class="form-control @error_class('printer_copies')" value="1">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Imprimir Logótipo no Talão?</label>
                                    <select name="printer_print_logo"
                                        class="form-control @error_class('printer_print_logo')">
                                        <option value="yes" selected>Sim (Gráfico no topo)</option>
                                        <option value="no">Não (Apenas texto)</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">Texto de Rodapé do Talão</label>
                                    <textarea name="printer_footer_note"
                                        class="form-control @error_class('printer_footer_note')"
                                        rows="2">Obrigado pela sua preferência! Os artigos adquiridos poderão ser trocados no prazo de 7 dias com a apresentação deste talão.</textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer"
                            style="padding-left: 0; padding-right: 0; display: flex; justify-content: space-between; align-items: center;">
                            <button type="button" class="btn btn-default" id="btn-test-printer">
                                <i class="fa-solid fa-print"></i> Imprimir Página de Teste
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Definições de Impressão
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ===== ABA 3: AGT / FACTURAÇÃO ===== -->
                <div class="tab-pane" id="tab-agt">
                    <form action="#" method="POST" data-ajax="true" novalidate>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">NIF do Emitente *</label>
                                    <input type="text" name="agt_issuer_nif"
                                        class="form-control @error_class('agt_issuer_nif')" value="5418290381" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Nº de Certificação do Software AGT *</label>
                                    <input type="text" name="agt_cert_number"
                                        class="form-control @error_class('agt_cert_number')" value="342/AGT/2026"
                                        required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Série da Factura *</label>
                                    <input type="text" name="agt_series" class="form-control @error_class('agt_series')"
                                        value="2026" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Ambiente de Operação</label>
                                    <select name="agt_environment" class="form-control @error_class('agt_environment')">
                                        <option value="production" selected>Produção (Faturas com valor legal)</option>
                                        <option value="test">Homologação / Testes</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Documento por Defeito</label>
                                    <select name="agt_doc_type" class="form-control @error_class('agt_doc_type')">
                                        <option value="FR" selected>Factura-Recibo (FR)</option>
                                        <option value="FT">Factura (FT)</option>
                                        <option value="VD">Venda a Dinheiro (VD)</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="control-label">Chave / Token de Assinatura Fiscal RSA</label>
                                    <input type="password" name="agt_token" id="agt_token"
                                        class="form-control @error_class('agt_token')"
                                        value="AGT_SECURE_TOKEN_RSA2048_CERT_NETPOS_ACTIVE">
                                    <div style="margin-top: 5px;">
                                        <button type="button" class="btn btn-default btn-xs"
                                            data-action="toggle-password" data-target="agt_token">
                                            <i class="fa-solid fa-eye"></i> Visualizar Token
                                        </button>
                                    </div>
                                    <span class="help-block" style="font-size: 11px; color: #888;">Utilizada para a
                                        criação do hash das faturas comunicadas à Administração Geral Tributária.</span>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer" style="padding-left: 0; padding-right: 0;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Configuração Fiscal AGT
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ===== ABA 4: SISTEMA ===== -->
                <div class="tab-pane" id="tab-system">
                    <form action="#" method="POST" data-ajax="true" novalidate>
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Taxa de IVA Padrão (%)</label>
                                    <input type="number" step="0.5" name="sys_default_vat"
                                        class="form-control @error_class('sys_default_vat')" value="14">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Prefixo da Factura</label>
                                    <input type="text" name="sys_invoice_prefix"
                                        class="form-control @error_class('sys_invoice_prefix')" value="FT">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Stock Mínimo Padrão</label>
                                    <input type="number" name="sys_default_min_stock"
                                        class="form-control @error_class('sys_default_min_stock')" value="10">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Formato de Data</label>
                                    <select name="sys_date_format" class="form-control @error_class('sys_date_format')">
                                        <option value="d/m/Y" selected>DD/MM/AAAA (ex: 21/10/2026)</option>
                                        <option value="Y-m-d">AAAA-MM-DD (ex: 2026-10-21)</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer" style="padding-left: 0; padding-right: 0;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Guardar Parâmetros do Sistema
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    {{-- FIM: CARDS --}}
</section>
@endsection


@section('model')
{{-- INÍCIO: MODAL (ADICIONAR PRODUTO) --}}
<div class="modal" id="modal-add-product" role="dialog" aria-labelledby="modal-add-title">
    <div class="modal-dialog modal-dialog-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-add-title"><i class="fa-solid fa-plus text-success"></i> Adicionar
                    Novo Produto</h4>
                <button type="button" class="close" data-modal-close aria-label="Fechar">&times;</button>
            </div>

            <form action="#" method="POST" data-ajax="true" id="form-add-product" novalidate>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nome do Produto *</label>
                                <input type="text" name="name" class="form-control @error_class('name')"
                                    placeholder="Ex: Arroz Agulha 1kg" required>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Código Interno *</label>
                                <input type="text" name="code" class="form-control @error_class('code')"
                                    placeholder="Ex: PRD-007" required>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Código de Barras</label>
                                <input type="text" name="barcode" class="form-control @error_class('barcode')"
                                    placeholder="EAN-13 ou código de barras">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="control-label">Categoria *</label>
                                <select name="category_id" class="form-control @error_class('category_id')" required>
                                    <option value="">Seleccione uma categoria</option>
                                    <option value="1">Alimentação</option>
                                    <option value="2">Bebidas</option>
                                    <option value="3">Higiene & Limpeza</option>
                                    <option value="4">Consumíveis POS</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="control-label">Preço de Custo (Kz)</label>
                                <input type="number" step="0.01" name="cost_price"
                                    class="form-control @error_class('cost_price')" placeholder="0,00">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="control-label">Preço de Venda (Kz) *</label>
                                <input type="number" step="0.01" name="sale_price"
                                    class="form-control @error_class('sale_price')" placeholder="0,00" required>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="control-label">Stock Inicial</label>
                                <input type="number" name="stock" class="form-control @error_class('stock')"
                                    placeholder="0" value="0">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="control-label">Stock Mínimo (Alerta)</label>
                                <input type="number" name="min_stock" class="form-control @error_class('min_stock')"
                                    placeholder="10" value="10">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <select name="status" class="form-control @error_class('status')">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="control-label">Descrição / Observações</label>
                                <textarea name="description" class="form-control @error_class('description')" rows="2"
                                    placeholder="Detalhes opcionais do produto..."></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar
                        Produto</button>
                </div>
            </form>

        </div>
    </div>
</div>
{{-- FIM: MODAL (ADICIONAR PRODUTO) --}}

{{-- INÍCIO: MODAL (CONFIRMAÇÃO DE ELIMINAÇÃO) --}}
<div class="modal" id="modal-delete-confirm" role="dialog">
    <div class="modal-dialog modal-dialog-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Confirmar
                    Eliminação</h4>
                <button type="button" class="close" data-modal-close>&times;</button>
            </div>
            <div class="modal-body">
                <p>Tem a certeza de que deseja eliminar este produto? Esta acção não poderá ser revertida.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                <button type="button" class="btn btn-danger"
                    onclick="window.closeModal('modal-delete-confirm'); window.showToast('success', 'Produto eliminado com sucesso.');">
                    <i class="fa-solid fa-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
{{-- FIM: MODAL (CONFIRMAÇÃO DE ELIMINAÇÃO) --}}
@endsection