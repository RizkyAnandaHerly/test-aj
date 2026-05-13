<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'status',
    ];

    protected $casts = [
        'status' => 'string', // active | inactive
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * A vendor has many inbound deliveries.
     */
    public function inbounds(): HasMany
    {
        return $this->hasMany(Inbound::class);
    }
}
