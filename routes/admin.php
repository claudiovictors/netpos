<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Rotas de administração protegidas obrigatoriamente por autenticação 
| e pelo middleware de controlo de acessos de administrador (AdminMiddleware).
|
*/

use Slenix\Http\Routing\Router;
use App\Middlewares\AdminMiddleware;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\CustomersController;
use App\Controllers\Admin\EmployeesController;
use App\Controllers\Admin\ReportsController;
use App\Controllers\Admin\SettingsController;

Router::group(['middleware' => ['auth', AdminMiddleware::class]], function (): void {

    //--------------------------------------------------------------------------
    // Dashboard Administrativa
    //--------------------------------------------------------------------------
    Router::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //--------------------------------------------------------------------------
    // Módulo de Produtos
    //--------------------------------------------------------------------------
    Router::get('/products', [ProductController::class, 'index'])->name('product');
    Router::post('/products', [ProductController::class, 'store'])->name('products.store');
    Router::post('/products/update', [ProductController::class, 'update'])->name('products.update');
    Router::post('/products/{id}/delete', [ProductController::class, 'delete'])->name('products.delete');

    //--------------------------------------------------------------------------
    // Módulo de Clientes (Gestão Administrativa)
    //--------------------------------------------------------------------------
    Router::get('/customers', [CustomersController::class, 'index'])->name('customers');
    Router::post('/customers/update', [CustomersController::class, 'update'])->name('customers.update');
    Router::post('/customers/{id}/delete', [CustomersController::class, 'delete'])->name('customers.delete');

    //--------------------------------------------------------------------------
    // Módulo de Funcionários
    //--------------------------------------------------------------------------
    Router::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    Router::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');
    Router::post('/employees/update', [EmployeesController::class, 'update'])->name('employees.update');
    Router::post('/employees/{id}/delete', [EmployeesController::class, 'delete'])->name('employees.delete');

    //--------------------------------------------------------------------------
    // Relatórios e Configurações Gerais
    //--------------------------------------------------------------------------
    Router::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Router::get('/settings', [SettingsController::class, 'index'])->name('settings');

});