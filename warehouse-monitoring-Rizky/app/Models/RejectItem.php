<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RejectItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inbound_id',
        'product_id',
        'inspector_id',
        'qty_rejected',
        'category',
        'quarantine_location',
        'reason',
    ];

    protected $casts = [
        'qty_rejected' => 'integer',
        'category'     => 'string',
    ];

    public function inbound(): BelongsTo
    {
        return $this->belongsTo(Inbound::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }
}
