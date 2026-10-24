<div class="pos-modal modal-lg" id="modal-return" role="dialog" aria-modal="true" aria-labelledby="modal-return-title"
    aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-return-title">
            <i class="fa-solid fa-rotate-left"></i> Devolução de Mercadoria / Nota de Crédito
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div class="pos-fieldset-group">
            <span class="pos-fieldset-legend">Documento de Venda Original</span>
            <div style="display: flex; gap: 8px;">
                <input type="text" id="return-invoice-search" class="form-control"
                    placeholder="Introduza o n.º da Factura (Ex: FR A2026/000124)" value="FR A2026/000124"
                    style="flex: 1;">
                <button type="button" class="pos-btn pos-btn-primary" style="padding: 0 16px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Localizar Fatura
                </button>
            </div>
        </div>
        <div class="pos-fieldset-group">
            <span class="pos-fieldset-legend">Selecionar Artigos a Devolver</span>
            <div style="max-height: 180px; overflow-y: auto;">
                <table class="pos-cart-table">
                    <thead>
                        <tr>
                            <th style="width: 5%; text-align: center;"><input type="checkbox" checked></th>
                            <th style="width: 20%;">Ref.</th>
                            <th style="width: 35%;">Designação</th>
                            <th style="width: 15%; text-align: center;">Qtd. Faturada</th>
                            <th style="width: 15%; text-align: center;">Qtd. Devolver</th>
                            <th style="width: 10%; text-align: right;">Preço</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;"><input type="checkbox" checked></td>
                            <td><strong>B001</strong></td>
                            <td>Água Mineral Pura 1.5L</td>
                            <td style="text-align: center;">2</td>
                            <td style="text-align: center;">
                                <input type="number" class="form-control" value="1" min="1" max="2"
                                    style="width: 60px; text-align: center; margin: 0 auto; height: 28px; padding: 2px;">
                            </td>
                            <td class="text-right">350,00 Kz</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><input type="checkbox" checked></td>
                            <td><strong>C001</strong></td>
                            <td>Rolo Térmico 80x80mm Caixa 50un</td>
                            <td style="text-align: center;">1</td>
                            <td style="text-align: center;">
                                <input type="number" class="form-control" value="1" min="1" max="1"
                                    style="width: 60px; text-align: center; margin: 0 auto; height: 28px; padding: 2px;">
                            </td>
                            <td class="text-right">18.500,00 Kz</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 0;">
            <label for="return-reason-text">Motivo da Devolução (Obrigatório por Lei para a AGT) *</label>
            <input type="text" id="return-reason-text" class="form-control"
                placeholder="Ex: Artigo com defeito de fabrico ou troca solicitada">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-danger" id="btn-confirm-return">
            <i class="fa-solid fa-check"></i> Emitir Nota de Crédito (NC)
        </button>
    </div>
</div>