<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tochka\ModelHistory\HistoryObserver;

/**
 * Create table for history records
 */
class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(HistoryObserver::getTableName(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('entity_name');
            $table->integer('entity_id');
            $table->string('action');
            $table->text('new_data')->nullable();
            $table->timestamp('changed_at');

            $table->index(['entity_name', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(HistoryObserver::getTableName());
    }
}
