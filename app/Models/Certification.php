<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'inbound_id',
        'product_id',
        'certifier_id',
        'certification_date',
        'certification_type',
        'lot_number',
        'standard_region',
        'document_path',
        'document_name',
        'status',
        'notes',
    ];

    protected $casts = [
        'certification_date' => 'date',
        'standard_region'    => 'string',
        'status'             => 'string',
    ];

    public function inbound(): BelongsTo
    {
        return $this->belongsTo(Inbound::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function certifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'certifier_id');
    }

    public function getDocumentUrlAttribute(): string
    {
        if (str_starts_with($this->document_path, 'uploads/')) {
            return asset($this->document_path);
        }
        return Storage::url($this->document_path);
    }
}
