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
                    <th style="width: 50px;">ID</th>
                    <th>Nome do Cliente</th>
                    <th>NIF</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Endereço</th>
                    <th class="text-center col-actions" style="width: 90px;">Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers['data'] as $customer)
                    <tr>
                        <td class="col-checkbox">
                            <label class="custom-checkbox-container">
                                <input type="checkbox" name="cust_ids[]" value="{{ $customer->id }}">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td>{{ $customer->id }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>
                            @if($customer->nif)
                                {{ $customer->nif }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $customer->phone ?? '—' }}</td>
                        <td>{{ $customer->email ?? '—' }}</td>
                        <td>{{ $customer->address ?? '—' }}</td>
                        <td class="col-actions">
                            <button type="button" class="action-btn" title="Editar"
                                    data-modal-open="modal-edit-customer"
                                    data-edit-customer="{{ json_encode($customer) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button type="button"
                                    class="action-btn action-delete"
                                    title="Eliminar"
                                    data-modal-open="modal-delete-customer"
                                    data-delete-id="{{ $customer->id }}"
                                    data-delete-name="{{ $customer->name }}"
                                    data-delete-url="{{ route('customers.delete', ['id' => $customer->id]) }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 30px;">
                            Nenhum cliente registado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Rodapé de paginação --}}
<div class="table-pagination-footer" id="customers-pagination">
    <div class="pagination-info" id="customers-info">
        @if(($customers['total'] ?? 0) > 0)
            A mostrar <strong>{{ $customers['from'] }}</strong> a <strong>{{ $customers['to'] }}</strong>
            de <strong>{{ $customers['total'] }}</strong> registos
        @else
            Total de <strong>0</strong> registos
        @endif
    </div>

    <div class="pagination-controls">
        <div class="pagination-per-page">
            <span>Mostrar</span>
            <select name="per_page" id="per-page-select">
                @foreach([10, 20, 50, 100] as $n)
                    <option value="{{ $n }}" {{ ($customers['per_page'] ?? 20) == $n ? 'selected' : '' }}>
                        {{ $n }}
                    </option>
                @endforeach
            </select>
            <span>registos</span>
        </div>

        <ul class="pagination" id="pagination-links">
            <li class="{{ ($customers['current_page'] ?? 1) <= 1 ? 'disabled' : '' }}">
                @if(($customers['current_page'] ?? 1) > 1)
                    <a href="#" data-page="{{ ($customers['current_page'] ?? 1) - 1 }}">&laquo;</a>
                @else
                    <span>&laquo;</span>
                @endif
            </li>

            @php
                $current = $customers['current_page'] ?? 1;
                $last    = $customers['last_page'] ?? 1;
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

            <li class="{{ ($customers['current_page'] ?? 1) >= ($customers['last_page'] ?? 1) ? 'disabled' : '' }}">
                @if(($customers['current_page'] ?? 1) < ($customers['last_page'] ?? 1))
                    <a href="#" data-page="{{ ($customers['current_page'] ?? 1) + 1 }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </li>
        </ul>
    </div>
</div>