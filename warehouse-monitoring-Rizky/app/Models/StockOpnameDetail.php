<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $table = 'stock_opname_details';

    protected $fillable = [
        'stock_opname_id',
        'product_location_id',
        'product_id',
        'system_qty',
        'physical_qty',
        'difference',
        'notes',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function productLocation(): BelongsTo
    {
        return $this->belongsTo(ProductLocation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
