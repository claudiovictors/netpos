<div class="pos-modal modal-sm" id="modal-price" role="dialog" aria-modal="true" aria-labelledby="modal-price-title"
    aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-price-title">
            <i class="fa-solid fa-tag"></i> Alterar Preço Unitário
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div style="font-weight: 700; font-size: 14px; margin-bottom: 10px; color: var(--pos-text-main);"
            id="price-target-item-name">
            Artigo Selecionado
        </div>
        <div class="form-group">
            <label for="input-modal-price">Novo Preço (Kz) *</label>
            <input type="number" id="input-modal-price" class="form-control"
                style="font-size: 20px; font-weight: 800; text-align: center; height: 46px;" min="0" step="10">
        </div>
        <div class="pos-keypad-grid" data-keypad-for="input-modal-price">
            <button type="button" class="pos-keypad-btn">7</button>
            <button type="button" class="pos-keypad-btn">8</button>
            <button type="button" class="pos-keypad-btn">9</button>
            <button type="button" class="pos-keypad-btn">4</button>
            <button type="button" class="pos-keypad-btn">5</button>
            <button type="button" class="pos-keypad-btn">6</button>
            <button type="button" class="pos-keypad-btn">1</button>
            <button type="button" class="pos-keypad-btn">2</button>
            <button type="button" class="pos-keypad-btn">3</button>
            <button type="button" class="pos-keypad-btn btn-clear">C</button>
            <button type="button" class="pos-keypad-btn">0</button>
            <button type="button" class="pos-keypad-btn btn-backspace"><i class="fa-solid fa-delete-left"></i></button>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-confirm-price">Confirmar</button>
    </div>
</div>