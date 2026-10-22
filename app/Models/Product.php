<?php

declare(strict_types=1);

namespace App\Models;

use Slenix\Database\Model;
use Slenix\Database\Relations\BelongsTo;

/**
 * Product model.
 *
 * @property int         $id
 * @property string      $name
 * @property string      $code
 * @property string|null $barcode
 * @property float       $cost_price
 * @property float       $sale_price
 * @property int         $stock
 * @property int         $min_stock
 * @property string      $status       'active' | 'inactive'
 * @property string|null $description
 * @property int         $category_id
 */
class Product extends Model
{
    protected string $table = 'products';
    protected string $primaryKey = 'id';

    /** @var string[] Mass-assignable attributes. */
    protected array $fillable = [
        'name',
        'code',
        'barcode',
        'cost_price',
        'sale_price',
        'stock',
        'min_stock',
        'status',
        'description',
        'category_id',
        'tenant_id'
    ];

    protected array $casts = [
        'id'              => 'integer',
        'tenant_id'       => 'integer',
        'category_id'     => 'integer',
        'stock'           => 'integer',
        'min_stock'       => 'integer',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}