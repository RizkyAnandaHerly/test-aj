<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustmentLog extends Model
{
    use HasFactory;

    protected $table = 'stock_adjustment_logs';

    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'location_id',
        'adjustment_qty',
        'adjustment_type',
        'reason',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
