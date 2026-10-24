<div class="pos-modal modal-sm" id="modal-open-session" role="dialog" aria-modal="true"
    aria-labelledby="modal-open-session-title" aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-open-session-title">
            <i class="fa-solid fa-cash-register"></i> Abertura de Turno de Caixa
        </h2>
    </div>
    <div class="pos-modal-body">
        <p style="margin-bottom: 12px; color: var(--pos-text-muted); font-size: 13px;">
            Para iniciar as operações de venda do posto, indique o valor inicial em caixa (Fundo de Maneio).
        </p>
        <div class="form-group">
            <label for="initial-cash">Fundo de Maneio (Kz) *</label>
            <input type="number" id="initial-cash" class="form-control" placeholder="0,00" min="0" step="100"
                value="10000">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-primary" id="btn-confirm-open-session" style="width: 100%;">
            <i class="fa-solid fa-check"></i> Abrir Turno e Iniciar Vendas
        </button>
    </div>
</div>