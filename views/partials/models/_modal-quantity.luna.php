<div class="pos-modal modal-sm" id="modal-quantity" role="dialog" aria-modal="true"
    aria-labelledby="modal-quantity-title" aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-quantity-title">
            <i class="fa-solid fa-calculator"></i> Alterar Quantidade
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div class="pos-keypad-container">
            <div class="pos-keypad-display" id="keypad-qty-display">1</div>
            <div class="pos-quick-steppers">
                <button type="button" class="pos-stepper-btn" id="btn-qty-minus">-1 Unid.</button>
                <button type="button" class="pos-stepper-btn" id="btn-qty-plus">+1 Unid.</button>
            </div>
            <div class="pos-keypad-grid">
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
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-confirm-qty">
            <i class="fa-solid fa-check"></i> Aplicar Quantidade
        </button>
    </div>
</div>