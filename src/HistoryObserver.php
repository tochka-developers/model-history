<?php

namespace Tochka\ModelHistory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * This listens for model events and produces a history record for each action on the model in question.
 *
 * @codeCoverageIgnore
 */
class HistoryObserver
{
    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';
    public const ACTION_RESTORE = 'restore';

    protected static ?string $tableName = null;

    public static function getTableName(): string
    {
        return self::$tableName ??
            self::$tableName = App::make('config')->get('model-history.table_name', 'history');
    }

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
            DB::table(self::getTableName())->insert([
                'entity_name' => $entity_name,
                'entity_id'   => $entity_id,
                'action'      => $action,
                'new_data'    => \json_encode($model->getDirty()),
                'changed_at'  => \date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $t) {
            Log::error(
                'Unable to save history entry',
                ['entity_name' => $entity_name, 'entity_id' => $entity_id, 'message' => $t->getMessage()]
            );
        }
    }
}
