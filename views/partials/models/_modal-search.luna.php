<div class="pos-modal modal-lg" id="modal-search-product" role="dialog" aria-modal="true"
    aria-labelledby="modal-search-product-title" aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-search-product-title">
            <i class="fa-solid fa-magnifying-glass"></i> Catálogo de Produtos
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div style="display: flex; gap: 10px; margin-bottom: 14px;">
            <div style="flex: 2;">
                <input type="text" id="product-search-input" class="form-control"
                    placeholder="Pesquisar por nome, código ou código de barras...">
            </div>
            <div style="flex: 1;">
                <select id="product-category-filter" class="form-control">
                    <option value="">Todas as Categorias</option>
                    <option value="Alimentação">Alimentação</option>
                    <option value="Bebidas">Bebidas</option>
                    <option value="Higiene">Higiene & Limpeza</option>
                    <option value="Consumíveis">Consumíveis POS</option>
                </select>
            </div>
        </div>
        <div style="max-height: 320px; overflow-y: auto; border: 1px solid var(--pos-border); border-radius: 3px;">
            <table class="pos-cart-table">
                <thead>
                    <tr>
                        <th style="width: 18%;">Código</th>
                        <th>Designação do Artigo</th>
                        <th style="width: 20%;">Categoria</th>
                        <th style="width: 15%; text-align: right;">Preço (c/ IVA)</th>
                        <th style="width: 12%; text-align: center;">Stock</th>
                    </tr>
                </thead>
                <tbody id="product-search-results">
                    <tr>
                        <td><strong>PRD-001</strong></td>
                        <td>Arroz Agulha 1kg</td>
                        <td>Alimentação</td>
                        <td class="text-right">1.850,00 Kz</td>
                        <td class="text-center"><strong>48</strong></td>
                    </tr>
                    <tr>
                        <td><strong>PRD-002</strong></td>
                        <td>Óleo Alimentar 1L</td>
                        <td>Alimentação</td>
                        <td class="text-right">2.400,00 Kz</td>
                        <td class="text-center"><strong>35</strong></td>
                    </tr>
                    <tr>
                        <td><strong>PRD-003</strong></td>
                        <td>Refrigerante Cola 330ml</td>
                        <td>Bebidas</td>
                        <td class="text-right">500,00 Kz</td>
                        <td class="text-center"><strong>120</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-add-selected-product">
            <i class="fa-solid fa-plus"></i> Adicionar Artigo
        </button>
    </div>
</div>