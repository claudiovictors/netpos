<div class="pos-modal modal-sm" id="modal-pin" role="dialog" aria-modal="true" aria-labelledby="modal-pin-title"
    aria-hidden="true">
    <div class="pos-modal-header" style="background-color: var(--pos-warning);">
        <h2 class="pos-modal-title" id="modal-pin-title">
            <i class="fa-solid fa-shield-halved"></i> Autorização de Gerente
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <p style="font-size: 13px; color: var(--pos-text-muted); margin-bottom: 10px;" id="pin-prompt-message">
            Esta operação requer autorização superior. Introduza o PIN de supervisor.
        </p>
        <div class="form-group">
            <input type="password" id="input-modal-pin" class="form-control"
                style="font-size: 24px; font-weight: 800; text-align: center; letter-spacing: 6px; height: 50px;"
                maxlength="8" placeholder="••••">
            <span class="help-block" id="pin-error-help"></span>
        </div>
        <div class="pos-keypad-grid" data-keypad-for="input-modal-pin">
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
        <p style="text-align: center; font-size: 11px; color: var(--pos-text-muted); margin-top: 8px;">
            PIN de teste mock: <strong>1234</strong>
        </p>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-primary" id="btn-confirm-pin">Validar PIN</button>
    </div>
</div>