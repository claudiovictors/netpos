<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| containing the "web" middleware group. Enjoy the elegance!
|
*/

declare(strict_types=1);

use Slenix\Http\Routing\Router;
use App\Controllers\PosController;
use App\Controllers\AuthController;
use App\Middlewares\CorsMiddleware;
use App\Controllers\Admin\CustomersController;

Router::globalMiddleware([CorsMiddleware::class]);

Router::group(['middleware' => ['guest']], function(): void {
    
    //--------------------------------------------------------
    // LOGIN - FORM
    //--------------------------------------------------------
    Router::view('/', 'auth.login')->name('login');
    Router::post('/sig-in', [AuthController::class, 'login']) ->name('sig-in')
        ->middleware('throttle:5,1');
});

Router::group(['middleware' => ['auth']], function(): void {
    //--------------------------------------------------------
    // PONTO DE VENDA - POS
    //--------------------------------------------------------
    Router::get('/point-of-sales', [PosController::class, 'index'])->name('pos');

    //--------------------------------------------------------
    // ADICIONAR CLIENTES - CLIENTES
    //--------------------------------------------------------
    Router::post('/customers', [CustomersController::class, 'store'])->name('customers.store');

    //--------------------------------------------------------
    // TERMINAR - FUNCIONÁRIO
    //--------------------------------------------------------
    Router::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

require_once __DIR__ .'/admin.php';