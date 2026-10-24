<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Web Routes
|--------------------------------------------------------------------------
|
| Este ficheiro é responsável por registar todas as rotas web da aplicação.
| As rotas são carregadas pelo router e divididas por grupos de middleware.
|
*/

use Slenix\Http\Routing\Router;
use App\Controllers\AuthController; // Ajuste o namespace se necessário
use App\Controllers\PosController;
use App\Controllers\Admin\CustomersController;
use App\Middlewares\CorsMiddleware;

/*
|--------------------------------------------------------------------------
| Global Middleware
|--------------------------------------------------------------------------
|
| Middlewares aplicados a todas as requisições que passam por este router.
|
*/
Router::globalMiddleware([CorsMiddleware::class]);

/*
|--------------------------------------------------------------------------
| Guest Routes (Visitantes / Não Autenticados)
|--------------------------------------------------------------------------
|
| Rotas acessíveis apenas por utilizadores que ainda não iniciaram sessão.
|
*/
Router::group(['middleware' => ['guest']], function (): void {
    
    // Página de Login (Raiz da aplicação)
    Router::view('/', 'auth.login')->name('login');

    // Processamento do Login (Protegido por Rate Limiting: max 5 tentativas por 1 minuto)
    Router::post('/sig-in', [AuthController::class, 'login'])
        ->name('sig-in')
        ->middleware('throttle:5,1');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Utilizadores Autenticados)
|--------------------------------------------------------------------------
|
| Rotas acessíveis por qualquer utilizador com sessão ativa.
|
*/
Router::group(['middleware' => ['auth']], function (): void {

    // Ponto de Venda (POS)
    Router::get('/point-of-sales', [PosController::class, 'index'])->name('pos');

    // Registo rápido de Clientes (geralmente usado via modais no POS ou outras views)
    Router::post('/customers', [CustomersController::class, 'store'])->name('customers.store');

    // Terminar Sessão (Logout)
    Router::post('/logout', [AuthController::class, 'logout'])->name('logout');

});

/*
|--------------------------------------------------------------------------
| Admin Routes Inclusion
|--------------------------------------------------------------------------
|
| Carrega o subconjunto de rotas dedicadas à administração do sistema.
|
*/
require_once __DIR__ . '/admin.php';