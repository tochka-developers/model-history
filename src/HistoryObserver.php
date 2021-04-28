<?php

namespace Tochka\ModelHistory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * This listens for model events and produces a history record for each action on the model in question.
 */
class HistoryObserver
{
    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';
    public const ACTION_RESTORE = 'restore';

    public function created(Model $model): void
    {
        self::saveData($model, self::ACTION_CREATE);
    }

    public function updated(Model $model): void
    {
        self::saveData($model, self::ACTION_UPDATE);
    }

    public function deleted(Model $model): void
    {
        self::saveData($model, self::ACTION_DELETE);
    }

    public function restored(Model $model): void
    {
        self::saveData($model, self::ACTION_RESTORE);
    }

    public static function saveData(Model $model, string $action): void
    {
        $entity_name = $model->getTable();
        $entity_id = $model->{$model->getKeyName()};
        try {
            $query = DB::table(self::getTableName());

            $query->insert([
                'entity_name' => $entity_name,
                'entity_id'   => $entity_id,
                'action'      => $action,
                'new_data'    => \json_encode($model->getDirty()),
                'changed_at'  => \date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $t) {
            Log::error('Unable to save history entry', ['entity_name' => $entity_name, 'entity_id' => $entity_id, 'message' => $t->getMessage()]);
        }
    }

    public static function getTableName(): string
    {
        return config('model-history.table_name', 'history');
    }
}
