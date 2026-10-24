<div class="pos-modal modal-sm" id="modal-discard" role="dialog" aria-modal="true" aria-labelledby="modal-discard-title"
    aria-hidden="true">
    <div class="pos-modal-header" style="background-color: var(--pos-danger);">
        <h2 class="pos-modal-title" id="modal-discard-title">
            <i class="fa-solid fa-triangle-exclamation"></i> Anular Venda em Curso
        </h2>
        <button type="button" class="pos-modal-close-btn" data-modal-close title="Fechar (Esc)"
            aria-label="Fechar janela">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="pos-modal-body" style="text-align: center; padding: 24px 16px;">
        <div style="font-size: 48px; color: var(--pos-danger); margin-bottom: 12px;">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <h3 style="font-size: 16px; font-weight: 700; color: var(--pos-text-main); margin-bottom: 8px;">
            Tem a certeza que deseja anular esta venda?
        </h3>
        <p style="font-size: 13px; color: var(--pos-text-muted); line-height: 1.4; margin-bottom: 0;">
            Todos os artigos registados no carrinho serão removidos e a sessão de venda atual será cancelada sem registo
            fiscal. Esta operação não pode ser revertida.
        </p>
    </div>
    <div class="pos-modal-footer">
        <button type="button" class="pos-btn pos-btn-default" data-modal-close>Cancelar</button>
        <button type="button" class="pos-btn pos-btn-danger" id="btn-confirm-discard-doc">
            <i class="fa-solid fa-trash-can"></i> Sim, Anular Venda
        </button>
    </div>
</div>