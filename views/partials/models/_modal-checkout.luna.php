<div class="pos-modal modal-lg" id="modal-checkout" role="dialog" aria-modal="true"
    aria-labelledby="modal-checkout-title" aria-hidden="true">
    <div class="pos-modal-header">
        <h2 class="pos-modal-title" id="modal-checkout-title">
            <i class="fa-solid fa-cash-register"></i> Finalizar Venda e Registar Pagamentos
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body">
        <div class="pos-checkout-grid">
            <div class="pos-checkout-left">
                <div class="pos-checkout-totals-box">
                    <div class="pos-checkout-row highlight-total">
                        <span>Total a Pagar:</span>
                        <span id="checkout-total-due">0,00 Kz</span>
                    </div>
                    <div class="pos-checkout-row highlight-paid">
                        <span>Total Entregue:</span>
                        <span id="checkout-total-paid">0,00 Kz</span>
                    </div>
                    <div class="pos-checkout-row highlight-balance" id="checkout-balance-row">
                        <span id="checkout-balance-label">Troco:</span>
                        <span id="checkout-balance-value">0,00 Kz</span>
                    </div>
                </div>
                <div>
                    <span
                        style="font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase;">
                        Meios de Pagamento (Pagamento Misto Disponível)
                    </span>
                    <div class="pos-payment-methods-grid" style="margin-top: 4px;">
                        <button type="button" class="pos-method-tile is-active" data-method="cash" data-name="Dinheiro">
                            <i class="fa-solid fa-money-bill-wave"></i> Dinheiro
                        </button>
                        <button type="button" class="pos-method-tile" data-method="tpa" data-name="TPA / Multicaixa">
                            <i class="fa-solid fa-credit-card"></i> TPA / Multicaixa
                        </button>
                        <button type="button" class="pos-method-tile" data-method="mcx" data-name="MCX Express">
                            <i class="fa-solid fa-mobile-screen"></i> MCX Express
                        </button>
                        <button type="button" class="pos-method-tile" data-method="transfer" data-name="Transferência">
                            <i class="fa-solid fa-building-columns"></i> Transferência
                        </button>
                    </div>
                </div>
                <div>
                    <span
                        style="font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase;">
                        Detalhamento por Meio de Pagamento
                    </span>
                    <div class="pos-active-methods-list" id="checkout-active-methods" style="margin-top: 4px;"></div>
                </div>
            </div>
            <div class="pos-checkout-right">
                <div>
                    <span
                        style="font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase;">
                        Atalhos Rápidos de Numerário
                    </span>
                    <div class="pos-quick-cash-grid" style="margin-top: 4px;">
                        <button type="button" class="pos-quick-cash-btn" data-action="exact">Valor Exato</button>
                        <button type="button" class="pos-quick-cash-btn" data-value="1000">1.000 Kz</button>
                        <button type="button" class="pos-quick-cash-btn" data-value="2000">2.000 Kz</button>
                        <button type="button" class="pos-quick-cash-btn" data-value="5000">5.000 Kz</button>
                        <button type="button" class="pos-quick-cash-btn" data-value="10000">10.000 Kz</button>
                        <button type="button" class="pos-quick-cash-btn" data-value="20000">20.000 Kz</button>
                    </div>
                </div>
                <div>
                    <span
                        style="font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase;">
                        Teclado Numérico
                    </span>
                    <div class="pos-keypad-grid" id="checkout-keypad" style="margin-top: 4px;">
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
                        <button type="button" class="pos-keypad-btn btn-backspace"><i
                                class="fa-solid fa-delete-left"></i></button>
                    </div>
                </div>
                <div class="pos-checkout-nif-warning" id="checkout-nif-warning" style="display: none;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <strong>Regra Fiscal AGT:</strong> Vendas a partir de 50.000,00 Kz exigem cliente com NIF
                    identificado.
                </div>
            </div>
        </div>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-success" id="btn-confirm-payment">
            <i class="fa-solid fa-check"></i> Finalizar Venda e Emitir Talão
        </button>
    </div>
</div>