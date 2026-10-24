@extends('layouts.app')

@section('title')
    Produtos | {{ config('app.name') }} Admin
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Produtos
            <small>Lista de produtos e gestão de inventário</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house"></i> Início</a></li>
            <li class="active">Produtos</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">

            {{-- Toolbar --}}
            <div class="table-toolbar">
                <div class="table-toolbar-left">
                    <label class="custom-checkbox-container" style="margin-right: 5px;">
                        <input type="checkbox" data-select-all title="Seleccionar todos">
                        <span class="checkmark"></span>
                    </label>

                    <div class="dropdown" data-dropdown-container>
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-dropdown-toggle>
                            <span>Acções</span> <span id="selected-batch-count"></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" onclick="event.preventDefault(); window.showToast('info', 'Por favor seleccione pelo menos um artigo.');">
                                    <i class="fa-solid fa-toggle-on text-success"></i> Activar seleccionados
                                </a>
                            </li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); window.showToast('info', 'Por favor seleccione pelo menos um artigo.');">
                                    <i class="fa-solid fa-toggle-off text-warning"></i> Desactivar seleccionados
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); window.showToast('danger', 'Eliminação em lote requer confirmação.');">
                                    <i class="fa-solid fa-trash text-danger"></i> Eliminar seleccionados
                                </a>
                            </li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm" data-action="refresh">
                        <i class="fa-solid fa-rotate btn-icon"></i>
                        <span class="btn-label">Actualizar</span>
                    </button>
                </div>

                <div class="table-toolbar-right">
                    <button type="button" class="btn btn-success btn-sm" data-modal-open="modal-add-product">
                        <i class="fa-solid fa-plus"></i> Novo
                    </button>

                    <div class="dropdown" data-dropdown-container>
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-dropdown-toggle>
                            <i class="fa-solid fa-download"></i> Exportar
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="#" data-action="export" data-format="Excel (XLSX)"><i class="fa-solid fa-file-excel text-success"></i> Exportar para Excel</a></li>
                            <li><a href="#" data-action="export" data-format="CSV"><i class="fa-solid fa-file-csv text-info"></i> Exportar para CSV</a></li>
                            <li><a href="#" data-action="export" data-format="PDF"><i class="fa-solid fa-file-pdf text-danger"></i> Exportar para PDF</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a href="#" data-action="print"><i class="fa-solid fa-print"></i> Imprimir Tabela</a></li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm btn-toggle-filter" data-action="toggle-filter">
                        <i class="fa-solid fa-filter"></i> Filtrar
                    </button>

                    <button type="button" class="btn btn-navy btn-sm btn-reset-filter" data-action="reset-filter">
                        <i class="fa-solid fa-rotate-left"></i> Limpar
                    </button>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="filter-box">
                <form id="form-product-filter">
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Pesquisa rápida</label>
                                <input type="text" name="search" class="form-control" placeholder="Nome, código ou código de barras...">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Categoria</label>
                                <select name="category_id" class="form-control">
                                    <option value="">Todas as categorias</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <select name="status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="active">Activo</option>
                                    <option value="inactive">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-2">
                            <div class="form-group">
                                <label class="control-label">Nível de Stock</label>
                                <select name="stock_level" class="form-control">
                                    <option value="">Todos os stocks</option>
                                    <option value="low">Apenas Stock Baixo</option>
                                    <option value="zero">Sem Stock (0 un)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-2" style="display: flex; align-items: flex-end; padding-bottom: 15px;">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa-solid fa-magnifying-glass"></i> Pesquisar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabela + paginação (carregadas via partial) --}}
            <div id="products-table-wrapper">
                @include('partials.products-table', ['products' => $products])
            </div>

        </div>
    </section>
@endsection

@section('model')
    {{-- Modal Adicionar --}}
    <div class="modal" id="modal-add-product" role="dialog" aria-labelledby="modal-add-title">
        <div class="modal-dialog modal-dialog-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-add-title">
                        <i class="fa-solid fa-plus text-success"></i> Adicionar Novo Produto
                    </h4>
                    <button type="button" class="close" data-modal-close aria-label="Fechar">&times;</button>
                </div>

                <form action="{{ route('products.store') }}" method="POST" data-product-form id="form-add-product" novalidate>
                    @csrf
                    <div class="modal-body">
                        @include('partials.form-fields')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-floppy-disk btn-icon"></i>
                            <span class="btn-label">Guardar Produto</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div class="modal" id="modal-edit-product" role="dialog" aria-labelledby="modal-edit-title">
        <div class="modal-dialog modal-dialog-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-edit-title">
                        <i class="fa-regular fa-pen-to-square text-primary"></i> Editar Produto
                    </h4>
                    <button type="button" class="close" data-modal-close aria-label="Fechar">&times;</button>
                </div>

                <form action="{{ route('products.update') }}" method="POST" data-product-form id="form-edit-product" novalidate>
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        @include('partials.form-fields')
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

    {{-- Modal Eliminar --}}
    <div class="modal" id="modal-delete-confirm" role="dialog">
        <div class="modal-dialog modal-dialog-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i> Confirmar Eliminação
                    </h4>
                    <button type="button" class="close" data-modal-close>&times;</button>
                </div>
                <div class="modal-body">
                    <p>Tem a certeza de que deseja eliminar este produto? Esta acção não poderá ser revertida.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-modal-close>Cancelar</button>
                    <form action="{{ route('products.delete', ['id' => 5]) }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-danger" onclick="window.closeModal('modal-delete-confirm'); window.showToast('success', 'Produto eliminado com sucesso.');">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
@endpush