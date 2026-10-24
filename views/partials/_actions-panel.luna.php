<aside class="pos-actions-panel" aria-label="Painel de ações rápidas e funções">

    {{-- VISTA A: GRELHA DE TILES (2 COLUNAS) --}}
    <div class="pos-view-container" id="pos-view-tiles">
        <div class="pos-tiles-grid">
            {{-- 1. Novo Documento --}}
            <button type="button" class="pos-tile-btn" id="btn-new-doc" title="Iniciar um novo documento de venda">
                <i class="fa-regular fa-file"></i>
                <span>Novo Documento</span>
            </button>

            {{-- 2. Fechar Conta --}}
            <button type="button" class="pos-tile-btn tile-checkout" id="btn-checkout" data-modal-open="modal-checkout"
                title="Finalizar venda e registar pagamentos (F9)">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Fechar Conta</span>
            </button>

            {{-- 3. Cliente --}}
            <button type="button" class="pos-tile-btn" id="btn-customer" data-modal-open="modal-customer"
                title="Associar ou criar cliente (F4)">
                <i class="fa-solid fa-user-plus"></i>
                <span>Cliente</span>
            </button>

            {{-- 4. Fechar com Desconto --}}
            <button type="button" class="pos-tile-btn" id="btn-discount" data-modal-open="modal-discount"
                title="Aplicar desconto percentual ou monetário (F7)">
                <i class="fa-solid fa-percent"></i>
                <span>Fechar c/ Desc.</span>
            </button>

            {{-- 5. Quantidade --}}
            <button type="button" class="pos-tile-btn" id="btn-qty" data-modal-open="modal-quantity"
                title="Alterar quantidade da linha selecionada (F3)">
                <i class="fa-solid fa-calculator"></i>
                <span>Quantidade</span>
            </button>

            {{-- 6. Preço --}}
            <button type="button" class="pos-tile-btn" id="btn-price" data-modal-open="modal-price"
                title="Alterar preço da linha selecionada (F6)">
                <i class="fa-solid fa-tags"></i>
                <span>Preço</span>
            </button>

            {{-- 7. Pesquisar Produto --}}
            <button type="button" class="pos-tile-btn" id="btn-search-product" data-modal-open="modal-search-product"
                title="Abrir catálogo geral de artigos (F2)">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Pesquisar Artigo</span>
            </button>

            {{-- 8. Eliminar Linha --}}
            <button type="button" class="pos-tile-btn tile-delete-line" id="btn-delete-line"
                title="Remover a linha selecionada do carrinho (Delete)">
                <i class="fa-solid fa-trash-can"></i>
                <span>Eliminar Linha</span>
            </button>

            {{-- 9. Informação do Documento --}}
            <button type="button" class="pos-tile-btn" id="btn-doc-info" data-modal-open="modal-doc-info"
                title="Ver detalhes do documento e último emitido">
                <i class="fa-solid fa-circle-info"></i>
                <span>Info. Documento</span>
            </button>

            {{-- 10. Gravar Temporário --}}
            <button type="button" class="pos-tile-btn tile-park" id="btn-park-doc"
                title="Suspender e guardar venda em memória (F8)">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Gravar Temp.</span>
            </button>

            {{-- 11. Recuperar Documento --}}
            <button type="button" class="pos-tile-btn" id="btn-recover-doc" data-modal-open="modal-parked"
                title="Retomar uma venda anteriormente suspensa">
                <i class="fa-solid fa-folder-open"></i>
                <span>Recuperar Doc.</span>
            </button>

            {{-- 12. Imprimir Último --}}
            <button type="button" class="pos-tile-btn" id="btn-print-last" title="Reimprimir o último talão emitido">
                <i class="fa-solid fa-print"></i>
                <span>Imprimir Último</span>
            </button>

            {{-- 13. Outras Opções --}}
            <button type="button" class="pos-tile-btn" id="btn-show-more-options"
                title="Ver operações fiscais e administrativas (F10)">
                <i class="fa-solid fa-bars"></i>
                <span>Outras Opções</span>
            </button>
        </div>
    </div>

    {{-- VISTA B: LISTA VERTICAL "OUTRAS OPÇÕES" (ESTILO IMAGEM 1) --}}
    <div class="pos-view-container is-hidden" id="pos-view-more">
        <div class="pos-more-options-list">
            {{-- Botão de regresso ao topo --}}
            <button type="button" class="pos-list-btn btn-back-to-tiles" id="btn-back-to-tiles"
                title="Regressar aos botões de venda">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Voltar ao Teclado de Vendas</span>
            </button>

            {{-- Opções Administrativas e de Documentos --}}
            <button type="button" class="pos-list-btn" id="btn-convert-doc" data-modal-open="modal-convert">
                <i class="fa-solid fa-file-export"></i>
                <span>Converter Documento</span>
            </button>

            <button type="button" class="pos-list-btn" id="btn-return-doc" data-modal-open="modal-return">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Referência de Documento (NC)</span>
            </button>

            <button type="button" class="pos-list-btn btn-discard-doc" id="btn-discard-doc"
                data-modal-open="modal-discard">
                <i class="fa-solid fa-trash-arrow-up"></i>
                <span>Eliminar Documento Atual</span>
            </button>

            <button type="button" class="pos-list-btn" id="btn-close-day" data-modal-open="modal-close-day">
                <i class="fa-solid fa-receipt"></i>
                <span>Fecho de Dia (Turno de Caixa)</span>
            </button>

            <button type="button" class="pos-list-btn" id="btn-lock-screen" data-modal-open="modal-lock-screen">
                <i class="fa-solid fa-lock"></i>
                <span>Bloquear Ecrã (Ctrl+L)</span>
            </button>

            <button type="button" class="pos-list-btn" id="btn-shortcuts" data-modal-open="modal-shortcuts">
                <i class="fa-solid fa-keyboard"></i>
                <span>Teclas de Atalho do POS</span>
            </button>

            {{-- Links de saída para o Backoffice --}}
            @if (auth()->user()->is_admin)
                <a href="{{ route('dashboard') }}" class="pos-list-btn">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Abrir o Backoffice</span>
                </a>

                <a href="{{ route('dashboard') }}" class="pos-list-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Voltar ao Menu Principal</span>
                </a>
            @endif
        </div>
    </div>

</aside>