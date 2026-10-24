<div class="pos-modal modal-lg" id="modal-parked" role="dialog" aria-modal="true" aria-labelledby="modal-parked-title"
    aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-parked-title">
            <i class="fa-solid fa-clock-rotate-left"></i> Documentos Suspensos (Vendas Temporárias)
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <p style="margin-bottom: 12px; color: var(--pos-text-muted); font-size: 13px;">
            Lista de vendas suspensas neste posto aguardando retoma ou finalização pelo operador.
        </p>
        <div style="max-height: 340px; overflow-y: auto; border: 1px solid var(--pos-border); border-radius: 3px;">
            <table class="pos-cart-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Ref. / ID</th>
                        <th style="width: 35%;">Cliente</th>
                        <th style="width: 15%;">Artigos</th>
                        <th style="width: 20%; text-align: right;">Total</th>
                        <th style="width: 15%; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody id="parked-sales-tbody">
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px; color: var(--pos-text-muted);">
                            A carregar vendas suspensas...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Fechar</button>
    </div>
</div>