<?php

declare(strict_types=1);

namespace App\Models;

use Slenix\Database\Model;

/**
 * Modelo de Clientes.
 */
class Customer extends Model
{
    protected string $table = 'customers';
    protected string $primaryKey = 'id';
    
    /** @var array<string> Atributos atribuíveis em massa. */
    protected array $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'nif',
        'address',
    ];
}