<div class="pos-modal modal-md" id="modal-doc-info" role="dialog" aria-modal="true"
    aria-labelledby="modal-doc-info-title" aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-doc-info-title">
            <i class="fa-solid fa-circle-info"></i> Informação do Documento / Última Venda
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div class="pos-fieldset-group">
            <span class="pos-fieldset-legend">Documento em Consulta</span>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 13px;">
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Tipo
                        / Série:</span>
                    <strong>Factura-Recibo (FR A2026)</strong>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Número:</span>
                    <strong id="doc-info-number">FR A2026/000124</strong>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Data
                        e Hora:</span>
                    <span id="doc-info-datetime">23/10/2026 14:32</span>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Operador:</span>
                    <span>João Oliveira (01)</span>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Cliente
                        / NIF:</span>
                    <span id="doc-info-client">Consumidor Final (999999999)</span>
                </div>
                <div>
                    <span
                        style="color: var(--pos-text-muted); font-size: 11px; text-transform: uppercase; display: block;">Total
                        Emitido:</span>
                    <strong id="doc-info-total" style="color: var(--pos-brand); font-size: 15px;">1.850,00 Kz</strong>
                </div>
            </div>
        </div>
        <div style="display: flex; gap: 8px; margin-bottom: 14px;">
            <button type="button" class="pos-btn pos-btn-primary" id="btn-print-last" style="flex: 1;">
                <i class="fa-solid fa-print"></i> Reimprimir Último Talão
            </button>
        </div>
        <div class="pos-fieldset-group" style="border-color: #f39c12; margin-bottom: 0;">
            <span class="pos-fieldset-legend" style="color: #b7791f;">Anular Documento Comercial</span>
            <p style="font-size: 12px; color: var(--pos-text-muted); margin-bottom: 8px;">
                A anulação emite um documento de estorno e comunica a invalidação à AGT. Requer motivo justificado e PIN
                de supervisor.
            </p>
            <div class="form-group" style="margin-bottom: 8px;">
                <label for="void-reason">Motivo da Anulação *</label>
                <input type="text" id="void-reason" class="form-control"
                    placeholder="Ex: Erro de introdução de artigos ou cliente desistiu">
                <span class="help-block"></span>
            </div>
            <div class="form-group" style="margin-bottom: 10px;">
                <label for="void-pin">PIN de Supervisor / Gerente *</label>
                <input type="password" id="void-pin" class="form-control"
                    placeholder="Introduza o PIN (padrão de teste: 1234)" maxlength="8">
                <span class="help-block"></span>
            </div>
            <button type="button" class="pos-btn pos-btn-danger" id="btn-confirm-void-doc" style="width: 100%;">
                <i class="fa-solid fa-ban"></i> Confirmar Anulação do Documento
            </button>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Fechar</button>
    </div>
</div>