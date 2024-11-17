<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('logs.logs_users_actions', function (Blueprint $table) {
            $table->foreign('user_id')->references('user_id')->on('system.system_users')->cascadeOnUpdate()
                ->noActionOnDelete();
        });

        Schema::table('logs.logs_users_actions', function (Blueprint $table) {
            $table->foreign('token_id')->references('token_id')->on('system.system_users_tokens')->cascadeOnUpdate()
                ->noActionOnDelete();
        });

        Schema::table('logs.log_herriot_requests', function (Blueprint $table) {
            $table->foreign('application_animal_id')->references('application_animal_id')->on('data.data_applications_animals')->cascadeOnUpdate()
                ->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs.log_herriot_requests', function (Blueprint $table) {
            $table->dropForeign('application_animal_id');
        });

        Schema::table('logs.logs_users_actions', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });

        Schema::table('logs.logs_users_actions', function (Blueprint $table) {
            $table->dropForeign('token_id');
        });

    }
};
