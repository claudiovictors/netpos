<section class="pos-sales-workspace" aria-label="Área de venda e carrinho">

    {{-- INÍCIO: RESUMO (LOGO E TOTAL GIGANTE) --}}
    <div class="pos-summary-block">
        {{-- Logótipo NetPos à esquerda --}}
        <div class="pos-summary-logo">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="NetPos" width="170" height="42">
            <span class="pos-summary-tagline">Sistema de Ponto de Venda</span>
        </div>

        {{-- TOTAL Gigante à direita (88px a 110px) --}}
        <div class="pos-summary-total-container">
            <span class="pos-summary-total-label">Total a Pagar</span>
            <div class="pos-summary-total-amount" id="pos-total-display">
                0,00 <span class="pos-summary-currency">Kz</span>
            </div>
        </div>
    </div>
    {{-- FIM: RESUMO --}}

    {{-- INÍCIO: FAIXA DE INFORMAÇÃO (METADADOS DA VENDA)--}}
    <div class="pos-info-strip" aria-label="Dados do documento atual">
        {{-- 1. Documento em curso --}}
        <div class="pos-info-item">
            <span class="pos-info-item-label">Documento</span>
            <div class="pos-info-item-value">
                <span class="pos-info-doc-badge" id="pos-doc-type-badge">FR</span>
                <span id="pos-doc-number-label">· Novo</span>
            </div>
        </div>

        {{-- 2. Vendedor atual --}}
        <div class="pos-info-item">
            <span class="pos-info-item-label">Vendedor</span>
            <div class="pos-info-item-value" title="Vendedor autenticado">
                {!! avatar(auth()->user()->name, 28) !!}
                <span>{{ auth()->user()->name }} <span class="pos-info-subtext">({{ auth()->user()->id }})</span></span>
            </div>
        </div>

        {{-- 3. Cliente atual (clicável para abrir modal de seleção) --}}
        <div class="pos-info-item pos-info-item-clickable" id="pos-client-trigger" data-modal-open="modal-customer"
            title="Clique para alterar cliente ou NIF">
            <span class="pos-info-item-label">Cliente (F4)</span>
            <div class="pos-info-item-value">
                <span class="pos-avatar pos-avatar-client">CF</span>
                <span id="pos-client-name">Consumidor Final</span>
                <span class="pos-info-subtext" id="pos-client-nif">· 999999999</span>
            </div>
        </div>

        {{-- 4. Desconto acumulado --}}
        <div class="pos-info-item">
            <span class="pos-info-item-label">Desconto Global</span>
            <div class="pos-info-item-value" id="pos-summary-discount">0,00 Kz</div>
        </div>

        {{-- 5. IVA acumulado --}}
        <div class="pos-info-item">
            <span class="pos-info-item-label">Imposto (IVA 14%)</span>
            <div class="pos-info-item-value" id="pos-summary-tax">0,00 Kz</div>
        </div>
    </div>
    {{-- FIM: FAIXA DE INFORMAÇÃO --}}

    {{-- INÍCIO: TABELA DE LINHAS DO CARRINHO --}}
    <div class="pos-cart-container">
        {{-- Tabela rolável de linhas de artigos --}}
        <div class="pos-cart-table-wrapper" tabindex="0" aria-label="Tabela de produtos registados">
            <table class="pos-cart-table" id="pos-cart-table">
                <thead>
                    <tr>
                        <th class="col-ref">Referência</th>
                        <th class="col-desc">Descrição</th>
                        <th class="col-qty">Quant.</th>
                        <th class="col-price">Preço</th>
                        <th class="col-desc-pct">Desc.</th>
                        <th class="col-tax">Imposto</th>
                        <th class="col-total">Total</th>
                    </tr>
                </thead>
                <tbody id="pos-cart-tbody">
                    {{-- Linhas demonstrativas como na referência --}}
                    <tr class="pos-cart-row is-selected" data-line-index="0" data-ref="SONY5">
                        <td><strong>SONY5</strong></td>
                        <td>LEITOR HI-FI 5 CD'S SONY COMPACT</td>
                        <td class="text-right">1,00</td>
                        <td class="text-right">20.000,00 Kz</td>
                        <td class="text-right">0,00%</td>
                        <td class="text-center"><span class="pos-tax-badge">IVA 14%</span></td>
                        <td class="text-right"><strong>20.000,00 Kz</strong></td>
                    </tr>
                    <tr class="pos-cart-row" data-line-index="1" data-ref="MDREC">
                        <td><strong>MDREC</strong></td>
                        <td>GRAVADOR/LEITOR DIGITAL MULTIMÉDIA</td>
                        <td class="text-right">1,00</td>
                        <td class="text-right">90.000,00 Kz</td>
                        <td class="text-right">0,00%</td>
                        <td class="text-center"><span class="pos-tax-badge">IVA 14%</span></td>
                        <td class="text-right"><strong>90.000,00 Kz</strong></td>
                    </tr>
                    <tr class="pos-cart-row" data-line-index="2" data-ref="SM58">
                        <td><strong>SM58</strong></td>
                        <td>MICROFONE DINÂMICO SHURE VOCAL</td>
                        <td class="text-right">1,00</td>
                        <td class="text-right">12.000,00 Kz</td>
                        <td class="text-right">0,00%</td>
                        <td class="text-center"><span class="pos-tax-badge">IVA 14%</span></td>
                        <td class="text-right"><strong>12.000,00 Kz</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Última linha: Introdução rápida por teclado ou leitor de código de barras --}}
        <div class="pos-cart-entry-container">
            <div class="pos-cart-input-wrapper">
                <i class="fa-solid fa-barcode pos-cart-barcode-icon" aria-hidden="true"></i>
                <input type="text" id="barcode-input" class="pos-cart-input"
                    placeholder="Ler código de barras ou introduzir referência (ex.: 3*PRD-001)..." autocomplete="off"
                    autofocus aria-label="Código de barras ou referência do artigo">
            </div>
            {{-- Botão de lupa para pesquisa rápida de artigos --}}
            <button type="button" class="pos-btn-search-trigger" id="btn-search-trigger"
                data-modal-open="modal-search-product" title="Pesquisar catálogo de produtos (F2)"
                aria-label="Pesquisar catálogo de produtos">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        {{-- Rodapé de Totais da Venda --}}
        <div class="pos-cart-footer">
            <div class="pos-cart-totals-group">
                <div class="pos-cart-total-item">
                    <span class="pos-cart-total-item-label">Subtotal Líquido:</span>
                    <span class="pos-cart-total-item-value" id="pos-footer-subtotal">107.017,54 Kz</span>
                </div>
                <div class="pos-cart-total-item">
                    <span class="pos-cart-total-item-label">Descontos:</span>
                    <span class="pos-cart-total-item-value" id="pos-footer-discount">0,00 Kz</span>
                </div>
                <div class="pos-cart-total-item">
                    <span class="pos-cart-total-item-label">Incidência IVA:</span>
                    <span class="pos-cart-total-item-value" id="pos-footer-tax">14.982,46 Kz</span>
                </div>
            </div>
            <div class="pos-cart-total-item highlight">
                <span class="pos-cart-total-item-label">Total a Liquidar:</span>
                <span class="pos-cart-total-item-value" id="pos-footer-total">122.000,00 Kz</span>
            </div>
        </div>
    </div>
    {{-- FIM: TABELA DE LINHAS DO CARRINHO --}}

</section>