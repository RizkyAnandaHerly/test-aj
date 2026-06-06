<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QcParameter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'qc_inspection_id',
        'parameter_name',
        'expected_value',
        'actual_value',
        'result',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'result' => 'string', // enum: pass | fail
    ];

    // ─── Relationships ───────────────────────────────────────────────────────

    /**
     * A QC parameter belongs to a QC inspection.
     */
    public function qcInspection(): BelongsTo
    {
        return $this->belongsTo(QcInspection::class);
    }
}
