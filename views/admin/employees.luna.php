@extends('layouts.app')

@section('title')
Funcionários | {{ config('app.name') }} Admin
@endsection

@section('content')
<section class="content-header">
    <h1>
        Funcionários
        <small>Gestão de utilizadores e perfis de acesso</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Início</a></li>
        <li class="active">Funcionários</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="table-toolbar">
            <div class="table-toolbar-left">
                <label class="custom-checkbox-container" style="margin-right: 5px;">
                    <input type="checkbox" data-select-all>
                    <span class="checkmark"></span>
                </label>

                <button type="button" class="btn btn-primary btn-sm" data-action="refresh">
                    <i class="fa-solid fa-rotate btn-icon"></i>
                    <span class="btn-label">Actualizar</span>
                </button>
            </div>

            <div class="table-toolbar-right">
                <button type="button" class="btn btn-success btn-sm" data-modal-open="modal-add-employee">
                    <i class="fa-solid fa-user-plus"></i> Novo Funcionário
                </button>

                <button type="button" class="btn btn-primary btn-sm btn-toggle-filter" data-action="toggle-filter">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>

                <button type="button" class="btn btn-navy btn-sm btn-reset-filter" data-action="reset-filter">
                    <i class="fa-solid fa-rotate-left"></i> Limpar
                </button>
            </div>
        </div>

        <div class="filter-box">
            <form id="form-employee-filter">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label class="control-label">Pesquisa rápida</label>
                            <input type="text" name="search" class="form-control" placeholder="Nome ou Email...">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="control-label">Estado</label>
                            <select name="status" class="form-control">
                                <option value="">Todos</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2" style="display: flex; align-items: flex-end; padding-bottom: 15px;">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="employees-table-wrapper">
            @include('partials.employees-table', ['employees' => $employees])
        </div>

    </div>
</section>
@endsection

@section('model')
{{-- Modal Adicionar Funcionário --}}
<div class="modal" id="modal-add-employee" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-solid fa-user-plus text-success"></i> Adicionar Novo Funcionário</h4>
                <button type="button" class="close" data-modal-close>&times;</button>
            </div>

            <form action="{{ route('employees.store') }}" method="POST" data-employee-form id="form-add-employee" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Nome Completo *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: Carlos Santos" required>
                        <span class="help-block"></span>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="carlos@exemplo.com" required>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telefone</label>
                                <input type="tel" name="phone" class="form-control" placeholder="(+244) 9XX XXX XXX">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <select name="status" class="form-control">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Palavra-passe *</label>
                                <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-floppy-disk btn-icon"></i>
                        <span class="btn-label">Guardar Funcionário</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar Funcionário --}}
<div class="modal" id="modal-edit-employee" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-regular fa-pen-to-square text-primary"></i> Editar Funcionário</h4>
                <button type="button" class="close" data-modal-close>&times;</button>
            </div>

            <form action="{{ route('employees.update') }}" method="POST" data-employee-form id="form-edit-employee" novalidate>
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Nome Completo *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: Carlos Santos" required>
                        <span class="help-block"></span>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Email *</label>
                                <input type="email" name="email" class="form-control" placeholder="carlos@exemplo.com" required>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Telefone</label>
                                <input type="tel" name="phone" class="form-control" placeholder="(+244) 9XX XXX XXX">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <select name="status" class="form-control">
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">Nova Palavra-passe</label>
                                <input type="password" name="password" class="form-control" placeholder="Deixar em branco para manter">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk btn-icon"></i>
                        <span class="btn-label">Guardar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Eliminar Funcionário --}}
<div class="modal" id="modal-delete-employee" role="dialog" aria-labelledby="modal-delete-employee-title">
    <div class="modal-dialog modal-dialog-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger" id="modal-delete-employee-title">
                    <i class="fa-solid fa-triangle-exclamation"></i> Eliminar Funcionário
                </h4>
                <button type="button" class="close" data-modal-close aria-label="Fechar">&times;</button>
            </div>
            <div class="modal-body">
                <p>
                    Tem a certeza de que deseja eliminar o funcionário
                    <strong id="delete-employee-name"></strong>?
                    Esta acção não poderá ser revertida.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete-employee"
                        data-delete-url="" data-employee-id="">
                    <i class="fa-solid fa-trash btn-icon"></i>
                    <span class="btn-label">Eliminar</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/employee.js') }}"></script>
@endpush