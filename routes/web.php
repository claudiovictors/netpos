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

use App\Controllers\AuthController;
use Slenix\Http\Routing\Router;
use App\Middlewares\CorsMiddleware;

Router::globalMiddleware([CorsMiddleware::class]);

Router::group(['middleware' => ['guest']], function(): void {
    Router::view('/', 'auth.login')->name('login');
    Router::post('/sig-in', [AuthController::class, 'login'])
        ->name('sig-in')
        ->middleware('throttle:5,1');
});

Router::group(['middleware' => ['auth']], function(): void {
    Router::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

require_once __DIR__ .'/admin.php';