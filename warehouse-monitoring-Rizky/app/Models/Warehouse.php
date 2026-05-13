<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'pic_name',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'string', // active | inactive
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * A warehouse has many storage locations.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
