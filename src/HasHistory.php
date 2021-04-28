<?php

namespace Tochka\ModelHistory;

/**
 * Use this in your model to enable saving history entries
 * @codeCoverageIgnore
 */
trait HasHistory
{
    public static function bootHasHistory(): void
    {
        static::observe(HistoryObserver::class);
    }
}
