{{-- Partial usado tanto na carga inicial como nas respostas AJAX de paginação/filtros --}}
<div class="box-body no-padding">
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="col-checkbox">
                        <label class="custom-checkbox-container">
                            <input type="checkbox" data-select-all>
                            <span class="checkmark"></span>
                        </label>
                    </th>
                    <th style="width: 70px;">
                        <span class="sortable-header">ID <i class="fa-solid fa-sort sort-icon"></i></span>
                    </th>
                    <th>Código</th>
                    <th>Nome do Produto</th>
                    <th>Categoria</th>
                    <th class="text-right">Preço de Venda</th>
                    <th class="text-center">Código de Barras</th>
                    <th class="text-center">Stock Actual</th>
                    <th class="text-center">Stock Mínimo</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center col-actions" style="width: 90px;">Acções</th>
                </tr>
            </thead>

            <tbody id="products-table-body">
                @forelse($products['data'] as $product)
                    <tr>
                        <td class="col-checkbox">
                            <label class="custom-checkbox-container">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->code }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td style="text-align: center;">{{ $product->category->name ?? '-' }}</td>
                        <td class="text-right">
                            <strong>{{ number_format((float) $product->sale_price, 2, ',', '.') }} Kz</strong>
                        </td>
                        <td class="text-center"><code>{{ $product->barcode ?? '-' }}</code></td>
                        <td class="text-center">
                            <span class="{{ $product->stock <= $product->min_stock ? 'stock-warning-text' : '' }}">
                                {{ $product->stock }} un
                            </span>
                        </td>
                        <td class="text-center">{{ $product->min_stock }} un</td>
                        <td class="text-center">
                            @if($product->status === 'active')
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="col-actions">
                            <button type="button" class="action-btn" title="Editar"
                                    data-modal-open="modal-edit-product"
                                    data-edit-product="{{ json_encode($product) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="action-btn action-delete" title="Eliminar"
                                    data-modal-open="modal-delete-confirm">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted" style="padding: 30px;">
                            Nenhum produto registado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Rodapé de paginação --}}
<div class="table-pagination-footer" id="products-pagination">
    <div class="pagination-info" id="products-info">
        @if(($products['total'] ?? 0) > 0)
            A mostrar <strong>{{ $products['from'] }}</strong> a <strong>{{ $products['to'] }}</strong>
            de <strong>{{ $products['total'] }}</strong> registos
        @else
            Total de <strong>0</strong> registos
        @endif
    </div>

    <div class="pagination-controls">
        <div class="pagination-per-page">
            <span>Mostrar</span>
            <select name="per_page" id="per-page-select">
                @foreach([10, 20, 50, 100] as $n)
                    <option value="{{ $n }}" {{ ($products['per_page'] ?? 20) == $n ? 'selected' : '' }}>
                        {{ $n }}
                    </option>
                @endforeach
            </select>
            <span>registos</span>
        </div>

        <ul class="pagination" id="pagination-links">
            {{-- Anterior --}}
            <li class="{{ ($products['current_page'] ?? 1) <= 1 ? 'disabled' : '' }}">
                @if(($products['current_page'] ?? 1) > 1)
                    <a href="#" data-page="{{ ($products['current_page'] ?? 1) - 1 }}">&laquo;</a>
                @else
                    <span>&laquo;</span>
                @endif
            </li>

            {{-- Números de página --}}
            @php
                $current = $products['current_page'] ?? 1;
                $last    = $products['last_page'] ?? 1;
                $start   = max(1, $current - 2);
                $end     = min($last, $current + 2);
            @endphp

            @if($start > 1)
                <li><a href="#" data-page="1">1</a></li>
                @if($start > 2)
                    <li class="disabled"><span>…</span></li>
                @endif
            @endif

            @for($i = $start; $i <= $end; $i++)
                <li class="{{ $i === $current ? 'active' : '' }}">
                    @if($i === $current)
                        <span>{{ $i }}</span>
                    @else
                        <a href="#" data-page="{{ $i }}">{{ $i }}</a>
                    @endif
                </li>
            @endfor

            @if($end < $last)
                @if($end < $last - 1)
                    <li class="disabled"><span>…</span></li>
                @endif
                <li><a href="#" data-page="{{ $last }}">{{ $last }}</a></li>
            @endif

            {{-- Seguinte --}}
            <li class="{{ ($products['current_page'] ?? 1) >= ($products['last_page'] ?? 1) ? 'disabled' : '' }}">
                @if(($products['current_page'] ?? 1) < ($products['last_page'] ?? 1))
                    <a href="#" data-page="{{ ($products['current_page'] ?? 1) + 1 }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </li>
        </ul>
    </div>
</div>