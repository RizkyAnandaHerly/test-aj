<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'inbound_id',
        'product_id',
        'packer_id',
        'quantity',
        'packaging_type',
        'package_weight',
        'package_dimensions',
        'label_code',
        'notes',
        'label_printed_at',
    ];

    protected $casts = [
        'quantity'          => 'integer',
        'label_printed_at'  => 'datetime',
    ];

    public function inbound(): BelongsTo
    {
        return $this->belongsTo(Inbound::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function packer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'packer_id');
    }
}
