<div class="pos-modal modal-md" id="modal-customer" role="dialog" aria-modal="true"
    aria-labelledby="modal-customer-title" aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-customer-title">
            <i class="fa-solid fa-user"></i> Seleccionar ou Criar Cliente
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div class="form-group">
            <input type="text" id="customer-search-input" class="form-control"
                placeholder="Pesquisar por nome ou NIF...">
        </div>
        <div
            style="max-height: 220px; overflow-y: auto; border: 1px solid var(--pos-border); border-radius: 3px; margin-bottom: 12px;">
            <table class="pos-cart-table">
                <thead>
                    <tr>
                        <th>Nome do Cliente</th>
                        <th style="width: 30%;">NIF</th>
                        <th style="width: 25%;">Telefone</th>
                    </tr>
                </thead>
                <tbody id="customer-search-results">
                    <tr class="is-selected">
                        <td><strong>Consumidor Final</strong></td>
                        <td>999999999</td>
                        <td>—</td>
                    </tr>
                    <tr>
                        <td>Roquita Isilda Pimentel</td>
                        <td>580436640</td>
                        <td>+244 923 111 222</td>
                    </tr>
                    <tr>
                        <td>Kwanza Distribuidora Lda</td>
                        <td>541289301</td>
                        <td>+244 912 345 678</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Fechar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-select-customer">
            <i class="fa-solid fa-check"></i> Associar Cliente à Venda
        </button>
    </div>
</div>