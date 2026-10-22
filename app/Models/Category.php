<?php

declare(strict_types=1);

namespace App\Models;

use Slenix\Database\Model;
use Slenix\Database\Relations\HasMany;

/**
 * Category model.
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 */
class Category extends Model
{
    protected string $table = 'categories';
    protected string $primaryKey = 'id';

    /** @var string[] Mass-assignable attributes. */
    protected array $fillable = ['name', 'slug'];

    protected array $casts = [
        'id'          => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }
}