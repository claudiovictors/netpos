<div class="pos-modal modal-md" id="modal-receipt" role="dialog" aria-modal="true" aria-labelledby="modal-receipt-title"
    aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-receipt-title">
            <i class="fa-solid fa-receipt"></i> Talão de Venda Emitido
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--pos-border);">
            <div style="display: flex; gap: 6px; align-items: center;">
                <span
                    style="font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase;">Papel:</span>
                <button type="button" class="pos-btn pos-btn-default" data-receipt-format="80mm"
                    style="min-height: 28px; padding: 2px 8px; font-size: 11px;">80 mm</button>
                <button type="button" class="pos-btn pos-btn-default" data-receipt-format="58mm"
                    style="min-height: 28px; padding: 2px 8px; font-size: 11px;">58 mm</button>
            </div>
            <div id="receipt-agt-chip" class="pos-receipt-agt-status accepted">
                <i class="fa-solid fa-circle-check"></i> AGT: Aceite (simulado)
            </div>
        </div>
        <div class="pos-receipt-preview-container">
            <div class="pos-thermal-receipt" id="pos-thermal-receipt-view">
                <div style="text-align: center; padding: 30px; color: var(--pos-text-muted);">
                    <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px;"></i>
                    <p style="margin-top: 8px; font-size: 12px;">A gerar talão certificado...</p>
                </div>
            </div>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Fechar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-print-receipt">
            <i class="fa-solid fa-print"></i> Imprimir Talão
        </button>
        <button type="button" class="pos-btn pos-btn-success" id="btn-receipt-new-sale">
            <i class="fa-solid fa-plus"></i> Nova Venda
        </button>
    </div>
</div>