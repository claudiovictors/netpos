<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Tenant;
use Slenix\Http\Request;
use Slenix\Http\Response;
use App\Http\Requests\LoginRequest;

/**
 * AuthController
 *
 * Handles all authentication-related actions including login,
 * registration, and logout using Slenix's built-in auth system.
 */
class AuthController
{
    /**
     * Handle a login attempt.
     *
     * Validates credentials via LoginRequest and attempts authentication.
     * Redirects to the dashboard on success, or back to login with an
     * error flash message on failure.
     *
     * POST /login
     */
    public function login(LoginRequest $req, Response $res)
    {
        $data = $req->validated();

        if (!auth()->attempt($data)) {
            return redirect()->withFlash('error', 'O email ou a palavra-passe estão incorrectos.')->to(route('login'));
        }

        $user = auth()->user();
        $tenant = Tenant::find($user->tenant_id);

        if ($user->status !== 'active' || !$tenant || $tenant->status !== 'active') {
            auth()->logout();
            return redirect()->withFlash('error', 'A sua conta está desactivada. Contacte o administrador.')->to(route('login'));
        }

        return redirect(route('dashboard'));
    }

    /**
     * Log the authenticated user out.
     *
     * Destroys the current session and redirects to the login page
     * with a success flash message.
     *
     * POST /logout
     */
    public function logout(Request $req, Response $res)
    {
        auth()->logout();
        return redirect(route('login'));
    }
}