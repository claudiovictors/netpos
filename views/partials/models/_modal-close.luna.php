<div class="pos-modal modal-md" id="modal-close-day" role="dialog" aria-modal="true"
    aria-labelledby="modal-close-day-title" aria-hidden="true">
    <div class="pos-modal-header" style="background-color: #222d32;">
        <h2 class="pos-modal-title" id="modal-close-day-title">
            <i class="fa-solid fa-lock"></i> Fecho de Turno de Caixa (Z-Report)
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <p style="margin-bottom: 12px; color: var(--pos-text-muted); font-size: 13px;">
            Conferência de gaveta e encerramento de operações do posto. O fecho gera o relatório de conferência Z e
            arquiva a sessão.
        </p>
        <div class="pos-fieldset-group">
            <span class="pos-fieldset-legend">Resumo da Sessão</span>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 13px;">
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Fundo
                        de Maneio Inicial:</span>
                    <strong id="close-day-initial">10.000,00 Kz</strong>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Vendas
                        em Numerário:</span>
                    <strong id="close-day-sales">122.000,00 Kz</strong>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Vendas
                        por TPA / MCX:</span>
                    <strong>85.400,00 Kz</strong>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Sangrias
                        / Saídas de Caixa:</span>
                    <span>0,00 Kz</span>
                </div>
            </div>
        </div>
        <div class="pos-fieldset-group" style="background-color: #fcfcfc;">
            <span class="pos-fieldset-legend">Conferência Física de Numerário</span>
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 6px 10px; background: #eef2f5; border-radius: 3px;">
                <span style="font-weight: 600; font-size: 13px;">Total em Dinheiro Esperado:</span>
                <strong id="close-day-expected" style="font-size: 15px; color: var(--pos-brand);">132.000,00 Kz</strong>
            </div>
            <div class="form-group" style="margin-bottom: 10px;">
                <label for="close-day-counted-cash">Dinheiro Efectivamente Contado em Gaveta (Kz) *</label>
                <input type="number" id="close-day-counted-cash" class="form-control" placeholder="0,00" step="100"
                    min="0" style="font-size: 16px; font-weight: 700; height: 42px;">
                <span class="help-block"></span>
            </div>
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; border: 1px dashed var(--pos-border); border-radius: 3px;">
                <span style="font-weight: 600; font-size: 13px;">Diferença de Caixa (Quebra / Sobra):</span>
                <strong id="close-day-difference" style="font-size: 16px; color: var(--pos-success);">0,00 Kz</strong>
            </div>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-danger" id="btn-confirm-close-day">
            <i class="fa-solid fa-lock"></i> Confirmar e Encerrar Caixa
        </button>
    </div>
</div>