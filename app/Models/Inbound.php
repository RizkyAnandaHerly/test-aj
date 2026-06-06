<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inbound extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Explicit because table name is not the standard plural-of-class-name.
     *
     * @var string
     */
    protected $table = 'inbound';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'qty',
        'vendor_id',
        'batch_no',
        'received_date',
        'received_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'received_date' => 'date',
        'qty'           => 'integer',
        'vendor_id'     => 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * An inbound record belongs to a product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * An inbound record belongs to a vendor.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * An inbound record was received by a user (staff).
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * An inbound record has one QC inspection.
     */
    public function qcInspection(): HasOne
    {
        return $this->hasOne(QcInspection::class);
    }
}
