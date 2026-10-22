<?php

declare(strict_types=1);

namespace App\Models;

use Slenix\Database\Model;

class Tenant extends Model
{
    protected string $table = 'tenants';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'name', 'email', 'phone', 'nif', 'address', 'currency', 'logo', 'status',
    ];
}