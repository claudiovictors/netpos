<?php
/*
|--------------------------------------------------------------------------
| AdminMiddleware Class
|--------------------------------------------------------------------------
|
| This middleware [describe the middleware functionality here].
|
*/

declare(strict_types=1);

namespace App\Middlewares;

use Slenix\Http\Request;
use Slenix\Http\Response;
use Slenix\Http\Middlewares\Middleware;

class AdminMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request  The HTTP request.
     * @param Response $response The HTTP response.
     * @param callable $next     The next handler in the pipeline.
     * @return mixed
     */
    public function handle(Request $request, Response $response, callable $next): mixed
    {
        if (!auth()->user()?->is_admin) {
            if ($request->expectsJson()) {
                $response->status(403)->json(['message' => 'Acesso negado. Esta ação requer privilégios de administrador.']);
                return null;
            }
            $response->status(403)->redirect(route('pos'));
            return null;
        }

        return $next($request, $response);
    }
}