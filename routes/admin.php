<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rotas de administração protegidas por autenticação e middleware de admin.
|
*/

use Slenix\Http\Routing\Router;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\CustomersController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\EmployeesController;
use App\Controllers\Admin\ReportsController;
use App\Controllers\Admin\SettingsController;
use App\Middlewares\AdminMiddleware;

Router::group(['middleware' => ['auth', AdminMiddleware::class]], function (): void {

    // Dashboard
    Router::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulo de Produtos
    Router::get('/products', [ProductController::class, 'index'])->name('product');
    Router::post('/products', [ProductController::class, 'store'])->name('products.store');
    Router::post('/products/update', [ProductController::class, 'update'])->name('products.update');

    // Outros módulos administrativos
    Router::get('/customers', [CustomersController::class, 'index'])->name('customers');
    Router::post('/customers', [CustomersController::class, 'store'])->name('customers.store');
    Router::post('/customers/update', [CustomersController::class, 'update'])->name('customers.update');
    
    // Módulo de Funcionários
    Router::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    Router::post('/employees', [EmployeesController::class, 'store'])->name('employees.store');
    Router::post('/employees/update', [EmployeesController::class, 'update'])->name('employees.update');
    
    Router::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Router::get('/settings', [SettingsController::class, 'index'])->name('settings');

});