{{--
  Shared product form fields (used by the add and edit modals).
  Server side: @error_class('field') prints the error CSS class when the field failed validation.
  Client side: assets/admin/js/product.js applies the same class (ERROR_CLASS).
--}}
<div class="row">
    <input type="number" name="tenant_id" value="{{ auth()->user()->tenant_id }}" hidden>
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label class="control-label">Nome do Produto *</label>
            <input type="text" name="name" class="form-control @error_class('name')" placeholder="Ex: Arroz Agulha 1kg" required>
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="form-group">
            <label class="control-label">Código Interno *</label>
            <input type="text" name="code" class="form-control @error_class('code')" placeholder="Ex: PRD-007" required>
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-3">
        <div class="form-group">
            <label class="control-label">Código de Barras</label>
            <input type="text" name="barcode" class="form-control @error_class('barcode')" placeholder="EAN-13 ou código de barras">
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="control-label">Categoria *</label>
            <select name="category_id" class="form-control @error_class('category_id')" required>
                <option value="">Seleccione uma categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="control-label">Preço de Custo (Kz)</label>
            <input type="number" step="0.01" min="0" name="cost_price" class="form-control @error_class('cost_price')" placeholder="0,00">
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="control-label">Preço de Venda (Kz) *</label>
            <input type="number" step="0.01" min="0" name="sale_price" class="form-control @error_class('sale_price')" placeholder="0,00" required>
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="control-label">Stock</label>
            <input type="number" min="0" name="stock" class="form-control @error_class('stock')" placeholder="0" value="0">
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="control-label">Stock Mínimo (Alerta)</label>
            <input type="number" min="0" name="min_stock" class="form-control @error_class('min_stock')" placeholder="10" value="10">
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="form-group">
            <label class="control-label">Estado</label>
            <select name="status" class="form-control @error_class('status')">
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
            </select>
            <span class="help-block"></span>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label class="control-label">Descrição / Observações</label>
            <textarea name="description" class="form-control @error_class('description')" rows="2" placeholder="Detalhes opcionais do produto..."></textarea>
            <span class="help-block"></span>
        </div>
    </div>
</div>