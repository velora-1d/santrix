<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    /**
     * Flag to enable/disable logging
     */
    public static $logEnabled = true;

    /**
     * Boot the trait
     */
    public static function bootLogsActivity(): void
    {
        // Log when model is created
        static::created(function ($model) {
            if (!static::$logEnabled) return;

            ActivityLog::logActivity(
                'Data ' . class_basename($model) . ' dibuat',
                $model,
                ['attributes' => $model->getAttributes()],
                'created'
            );
        });

        // Log when model is updated
        static::updated(function ($model) {
            if (!static::$logEnabled) return;

            $changes = $model->getChanges();
            $original = [];
            
            foreach (array_keys($changes) as $key) {
                if ($key !== 'updated_at') {
                    $original[$key] = $model->getOriginal($key);
                }
            }
            
            // Remove updated_at from changes
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                ActivityLog::logActivity(
                    'Data ' . class_basename($model) . ' diperbarui',
                    $model,
                    [
                        'old' => $original,
                        'attributes' => $changes
                    ],
                    'updated'
                );
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            if (!static::$logEnabled) return;

            ActivityLog::logActivity(
                'Data ' . class_basename($model) . ' dihapus',
                $model,
                ['old' => $model->getAttributes()],
                'deleted'
            );
        });
    }

    /**
     * Get all activities for this model
     */
    public function activities()
    {
        return ActivityLog::forSubject($this)->latest()->get();
    }
}
