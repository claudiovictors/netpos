@extends('layouts.app')

@section('title')
Clientes | {{ config('app.name') }} Admin
@endsection

@section('content')
<section class="content-header">
    <h1>
        Clientes
        <small>Base de dados de clientes, NIF e contactos</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Início</a></li>
        <li class="active">Clientes</li>
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
                <button type="button" class="btn btn-success btn-sm" data-modal-open="modal-add-customer">
                    <i class="fa-solid fa-user-plus"></i> Novo Cliente
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
            <form id="form-customer-filter">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label class="control-label">Pesquisa rápida</label>
                            <input type="text" name="search" class="form-control" placeholder="Nome, NIF, telefone ou email...">
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display: flex; align-items: flex-end; padding-bottom: 15px;">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-magnifying-glass"></i> Pesquisar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="customers-table-wrapper">
            @include('partials.customers-table', ['customers' => $customers])
        </div>

    </div>
</section>
@endsection

@section('model')
{{-- Modal Adicionar Cliente --}}
<div class="modal" id="modal-add-customer" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-solid fa-user-plus text-success"></i> Adicionar Novo Cliente</h4>
                <button type="button" class="close" data-modal-close>&times;</button>
            </div>

            <form action="{{ route('customers.store') }}" method="POST" data-customer-form id="form-add-customer" novalidate>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Nome Completo / Razão Social *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: João Silva ou Empresa X, Lda" required>
                        <span class="help-block"></span>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">NIF</label>
                                <input type="text" name="nif" class="form-control" placeholder="Ex: 5418290381">
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

                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="cliente@dominio.com">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Endereço de Facturação</label>
                        <input type="text" name="address" class="form-control" placeholder="Rua, Bairro, Província">
                        <span class="help-block"></span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-floppy-disk btn-icon"></i>
                        <span class="btn-label">Guardar Cliente</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Editar Cliente --}}
<div class="modal" id="modal-edit-customer" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa-regular fa-pen-to-square text-primary"></i> Editar Cliente</h4>
                <button type="button" class="close" data-modal-close>&times;</button>
            </div>

            <form action="{{ route('customers.update') }}" method="POST" data-customer-form id="form-edit-customer" novalidate>
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label">Nome Completo / Razão Social *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: João Silva ou Empresa X, Lda" required>
                        <span class="help-block"></span>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="control-label">NIF</label>
                                <input type="text" name="nif" class="form-control" placeholder="Ex: 5418290381">
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

                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="cliente@dominio.com">
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Endereço de Facturação</label>
                        <input type="text" name="address" class="form-control" placeholder="Rua, Bairro, Província">
                        <span class="help-block"></span>
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

{{-- Modal Eliminar Cliente --}}
<div class="modal" id="modal-delete-customer" role="dialog" aria-labelledby="modal-delete-customer-title">
    <div class="modal-dialog modal-dialog-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger" id="modal-delete-customer-title">
                    <i class="fa-solid fa-triangle-exclamation"></i> Eliminar Cliente
                </h4>
                <button type="button" class="close" data-modal-close aria-label="Fechar">&times;</button>
            </div>
            <div class="modal-body">
                <p>
                    Tem a certeza de que deseja eliminar o cliente
                    <strong id="delete-customer-name"></strong>?
                    Esta acção não poderá ser revertida.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete-customer"
                        data-delete-url="" data-customer-id="">
                    <i class="fa-solid fa-trash btn-icon"></i>
                    <span class="btn-label">Eliminar</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/customer.js') }}"></script>
@endpush