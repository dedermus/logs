<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('logs.logs_users_actions'))
        {
            Schema::create('logs.logs_users_actions', function (Blueprint $table)
            {
                $table->comment('Лог запросов пользователя');
                $table->increments('log_id')->comment('Инкремент');
                $table->integer('user_id')->nullable(false)->comment('ID пользователя в таблице SYSTEM.SYSTEM_USERS');
                $table->integer('token_id')->nullable(true)->default(null)->comment('ID токена в таблице SYSTEM.SYSTEM_USERS_TOKENS');
                $table->string('action_module', 32)->nullable(false)->comment('Название модуля в таблице SYSTEM.SYSTEM_MODULES');
                $table->string('action_method', 64)->nullable(false)->comment('Название метода в таблице SYSTEM.SYSTEM_MODULES_ACTIONS');
                $table->text('action_data')->nullable(true)->default(null)->comment('Данные запроса');
                $table->timestamp('action_created_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Дата создания записи');
                $table->timestamp('update_at')->nullable(false)->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Дата удаления записи');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs.logs_users_actions');
    }
};
