<?php

namespace App\Traits;

use App\Models\History;

trait LogTrait
{
    public static function logChanges(string $action, $after = null, $before = null): void
    {
        [$beforeData, $afterData, $model] = [
            (null !== $before) ? json_encode($before->getOriginal()) : null,
            (null !== $after) ? json_encode($after->getOriginal()) : null,
            $after ?? $before
        ];

        History::create([
            'model_id' => $model->id,
            'model_name' => get_class($model),
            'before' => $beforeData,
            'after' => $afterData,
            'action' => $action,
        ]);
    }

    public static function deleteLog(string $action, string $modelId): void
    {
        History::whereModelId($modelId)->where($action)->delete();
    }
}
