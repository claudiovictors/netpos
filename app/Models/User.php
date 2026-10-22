<?php

declare(strict_types=1);

namespace App\Models;

use Slenix\Database\Model;
use Slenix\Supports\Auth\Traits\Authenticatable;
use Slenix\Supports\Auth\Traits\HasRoles;

/**
 * Modelo de Utilizador / Funcionário.
 */
class User extends Model
{
    use Authenticatable, HasRoles;

    protected string $table = 'users';
    protected string $primaryKey = 'id';

    /** @var array<string> Atributos atribuíveis em massa. */
    protected array $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
        'status',
        'last_login_at',
    ];
}