<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_order_id',
        'created_by',
        'document_type',
        'document_number',
        'issued_date',
        'notes',
        'file_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'datetime',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
