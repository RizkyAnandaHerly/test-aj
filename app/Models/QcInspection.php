<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QcInspection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'inbound_id',
        'product_id',
        'inspector_id',
        'inspection_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'inspection_date' => 'date',
        'status'          => 'string', // enum: pass | fail | partial
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * A QC inspection belongs to an inbound record.
     */
    public function inbound(): BelongsTo
    {
        return $this->belongsTo(Inbound::class);
    }

    /**
     * A QC inspection belongs to a product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * A QC inspection was conducted by a user (inspector).
     */
    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    /**
     * A QC inspection has many parameter check rows.
     */
    public function parameters(): HasMany
    {
        return $this->hasMany(QcParameter::class);
    }
}
