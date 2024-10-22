<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('logs.log_herriot_requests'))
        {
            Schema::create('logs.log_herriot_requests', function (Blueprint $table)
            {
                $table->comment('Лог запросов в хорриот');
                $table->increments('log_herriot_requests_id')->comment('Инкремент');
                $table->integer('application_animal_id')->nullable(false)->comment('ID записи в таблице data.data_applications_animals');
                $table->text('application_request_herriot')->nullable(true)->default(null)->comment('ответ от хорриот при отправке на регистрацию');
                $table->text('application_response_herriot')->nullable(true)->default(null)->comment('запрос в хорриот при отправке на регистрацию');
                $table->text('application_request_application_herriot')->nullable(true)->default(null)->comment('запрос в хорриот для проверки статуса регистрации');
                $table->text('application_response_application_herriot')->nullable(true)->default(null)->comment('ответ от хорриот для проверки статуса регистрации');
                // Это поля "created_at" и "updated_at".
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs.log_herriot_requests');
    }
};
