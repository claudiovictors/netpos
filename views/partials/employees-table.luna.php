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
                    <th>Nome do Funcionário</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center col-actions" style="width: 90px;">Acções</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees['data'] as $employee)
                    <tr>
                        <td class="col-checkbox">
                            <label class="custom-checkbox-container">
                                <input type="checkbox" name="emp_ids[]" value="{{ $employee->id }}">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td>{{ $employee->id }}</td>
                        <td><strong>{{ $employee->name }}</strong></td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->phone ?? '—' }}</td>
                        <td class="text-center">
                            @if(($employee->status ?? 'active') === 'active')
                                <span class="label label-success">Activo</span>
                            @else
                                <span class="label label-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="col-actions">
                            <button type="button" class="action-btn" title="Editar"
                                    data-modal-open="modal-edit-employee"
                                    data-edit-employee="{{ json_encode($employee) }}">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                            <button type="button"
                                    class="action-btn action-delete"
                                    title="Eliminar"
                                    data-modal-open="modal-delete-employee"
                                    data-delete-id="{{ $employee->id }}"
                                    data-delete-name="{{ $employee->name }}"
                                    data-delete-url="{{ route('employees.delete', ['id' => $employee->id]) }}">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                            Nenhum funcionário registado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Rodapé de paginação --}}
<div class="table-pagination-footer" id="employees-pagination">
    <div class="pagination-info" id="employees-info">
        @if(($employees['total'] ?? 0) > 0)
            A mostrar <strong>{{ $employees['from'] }}</strong> a <strong>{{ $employees['to'] }}</strong>
            de <strong>{{ $employees['total'] }}</strong> registos
        @else
            Total de <strong>0</strong> registos
        @endif
    </div>

    <div class="pagination-controls">
        <div class="pagination-per-page">
            <span>Mostrar</span>
            <select name="per_page" id="per-page-select">
                @foreach([10, 20, 50, 100] as $n)
                    <option value="{{ $n }}" {{ ($employees['per_page'] ?? 20) == $n ? 'selected' : '' }}>
                        {{ $n }}
                    </option>
                @endforeach
            </select>
            <span>registos</span>
        </div>

        <ul class="pagination" id="pagination-links">
            <li class="{{ ($employees['current_page'] ?? 1) <= 1 ? 'disabled' : '' }}">
                @if(($employees['current_page'] ?? 1) > 1)
                    <a href="#" data-page="{{ ($employees['current_page'] ?? 1) - 1 }}">&laquo;</a>
                @else
                    <span>&laquo;</span>
                @endif
            </li>

            @php
                $current = $employees['current_page'] ?? 1;
                $last    = $employees['last_page'] ?? 1;
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

            <li class="{{ ($employees['current_page'] ?? 1) >= ($employees['last_page'] ?? 1) ? 'disabled' : '' }}">
                @if(($employees['current_page'] ?? 1) < ($employees['last_page'] ?? 1))
                    <a href="#" data-page="{{ ($employees['current_page'] ?? 1) + 1 }}">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </li>
        </ul>
    </div>
</div>