<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sku',
        'name',
        'category',
        'unit',
        'description',
        'stock_qty',
        'min_stock',
        'image',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'stock_qty' => 'integer',
        'min_stock' => 'integer',
        'status'    => 'string', // enum: active | inactive
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Whether the product stock is below minimum threshold.
     */
    public function isLowStock(): bool
    {
        return $this->stock_qty <= $this->min_stock;
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * A product has many inbound records.
     */
    public function inbounds(): HasMany
    {
        return $this->hasMany(Inbound::class);
    }

    /**
     * A product has many product-location pivot records.
     */
    public function productLocations(): HasMany
    {
        return $this->hasMany(ProductLocation::class);
    }

    /**
     * A product has many QC inspections.
     */
    public function qcInspections(): HasMany
    {
        return $this->hasMany(QcInspection::class);
    }
}
