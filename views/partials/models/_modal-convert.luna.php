<div class="pos-modal modal-lg" id="modal-convert" role="dialog" aria-modal="true" aria-labelledby="modal-convert-title"
    aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-convert-title">
            <i class="fa-solid fa-file-invoice"></i> Conversão de Documentos
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div class="pos-fieldset-group">
            <span class="pos-fieldset-legend">Entidade</span>
            <div style="font-weight: 600; color: var(--pos-text-main); font-size: 13px;">
                1 - Roquita Isilda Pimentel
            </div>
        </div>
        <div style="display: grid; grid-template-columns: 240px 110px 1fr; gap: 10px; align-items: center;">
            <div class="pos-fieldset-group" style="margin-bottom: 0;">
                <span class="pos-fieldset-legend">Documento a adicionar</span>
                <div class="form-group" style="margin-bottom: 8px;">
                    <select class="form-control" id="convert-doc-type">
                        <option value="Orçamento">Orçamento</option>
                        <option value="Pró-forma">Factura Pró-forma</option>
                        <option value="Encomenda">Nota de Encomenda</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 8px;">
                    <select class="form-control" id="convert-doc-series">
                        <option value="Serie1">Série 1</option>
                        <option value="Serie2">Série 2</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <div style="display: flex; gap: 4px;">
                        <input type="number" class="form-control" id="convert-doc-number" value="1" min="1">
                        <button type="button" class="pos-btn pos-btn-primary" style="min-height: 38px; padding: 0 10px;"
                            title="Pesquisar documento">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px; justify-content: center;">
                <button type="button" class="pos-btn pos-btn-primary"
                    style="font-size: 11px; padding: 6px 8px; min-height: 38px;">
                    Adicionar &gt;&gt;&gt;
                </button>
                <button type="button" class="pos-btn pos-btn-primary"
                    style="font-size: 11px; padding: 6px 8px; min-height: 38px;">
                    &lt;&lt;&lt; Remover
                </button>
            </div>
            <div class="pos-fieldset-group" style="margin-bottom: 0;">
                <span class="pos-fieldset-legend">Documentos Selecionados</span>
                <div style="max-height: 160px; overflow-y: auto;">
                    <table class="pos-cart-table">
                        <thead>
                            <tr>
                                <th>N.º Doc.</th>
                                <th>Data</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="is-selected">
                                <td><strong>ORC 1/1</strong></td>
                                <td>22/10/2026</td>
                                <td class="text-right"><strong>73.000,00 Kz</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-confirm-convert">
            Seguinte
        </button>
    </div>
</div>