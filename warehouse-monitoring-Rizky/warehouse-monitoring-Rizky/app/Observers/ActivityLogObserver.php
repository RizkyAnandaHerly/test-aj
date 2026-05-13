<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogObserver
{
    /**
     * Models that should NOT be observed (avoid infinite loops).
     */
    private const EXCLUDED = [
        ActivityLog::class,
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function shouldSkip(Model $model): bool
    {
        return in_array(get_class($model), self::EXCLUDED, true);
    }

    private function userName(): string
    {
        return Auth::check() ? Auth::user()->name : 'System';
    }

    private function roleName(): ?string
    {
        if (! Auth::check()) {
            return null;
        }
        try {
            return Auth::user()->role->name ?? 'unknown';
        } catch (\Throwable) {
            return 'unknown';
        }
    }

    private function writeLog(
        Model   $model,
        string  $action,
        ?array  $oldValues,
        ?array  $newValues,
        string  $description
    ): void {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'role_name'   => $this->roleName(),
            'action'      => $action,
            'model_type'  => class_basename($model),
            'model_id'    => $model->getKey(),
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'created_at'  => now(),
        ]);
    }

    // ─── Observers ───────────────────────────────────────────────────────────

    public function created(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        $type = class_basename($model);
        $user = $this->userName();

        $this->writeLog(
            $model,
            'create',
            null,
            $model->getAttributes(),
            "{$user} menambahkan data {$type} (ID: {$model->getKey()})"
        );
    }

    public function updated(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        // Only log if something actually changed
        if (empty($model->getDirty())) {
            return;
        }

        $type = class_basename($model);
        $user = $this->userName();

        // Build old / new snapshots only for changed columns
        $changed = array_keys($model->getDirty());
        $oldVals = array_intersect_key($model->getOriginal(), array_flip($changed));
        $newVals = array_intersect_key($model->getAttributes(), array_flip($changed));

        $this->writeLog(
            $model,
            'update',
            $oldVals,
            $newVals,
            "{$user} mengubah data {$type} (ID: {$model->getKey()})"
        );
    }

    public function deleted(Model $model): void
    {
        if ($this->shouldSkip($model)) {
            return;
        }

        $type = class_basename($model);
        $user = $this->userName();

        $this->writeLog(
            $model,
            'delete',
            $model->getAttributes(),
            null,
            "{$user} menghapus data {$type} (ID: {$model->getKey()})"
        );
    }
}
