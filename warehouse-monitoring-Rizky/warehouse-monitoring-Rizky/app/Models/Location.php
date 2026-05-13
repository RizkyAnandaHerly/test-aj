<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'warehouse_id',
        'zone',
        'rack_code',
        'pallet_code',
        'floor_level',
        'capacity',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'warehouse_id' => 'integer',
        'floor_level'  => 'integer',
        'capacity'     => 'integer',
        'status'       => 'string', // enum: available | full | reserved
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Human-readable label, e.g. "A / R-01 / P-02 (L1)".
     */
    public function getFullLabelAttribute(): string
    {
        $pallet = $this->pallet_code ? " / {$this->pallet_code}" : '';

        return "{$this->zone} / {$this->rack_code}{$pallet} (L{$this->floor_level})";
    }

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * A location belongs to a warehouse.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * A location has many product-location pivot records.
     */
    public function productLocations(): HasMany
    {
        return $this->hasMany(ProductLocation::class);
    }
}
