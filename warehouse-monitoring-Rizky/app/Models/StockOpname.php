<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'created_by',
        'opname_number',
        'opname_date',
        'status',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'opname_date' => 'date',
            'completed_at' => 'datetime',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(StockOpnameDetail::class);
    }

    public function adjustmentLogs(): HasMany
    {
        return $this->hasMany(StockAdjustmentLog::class);
    }
}
