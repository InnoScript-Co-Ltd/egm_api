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
        Schema::create('devices', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('user_type');
            $table->snowflakeId('user_id')->unique();
            $table->float('mem_used', 19, 2)->nullable();
            $table->float('disk_free', 19, 2)->nullable();
            $table->float('free_disk_total', 19, 2)->nullable();
            $table->float('real_disk_free', 19, 2)->nullable();
            $table->float('real_disk_total', 19, 2)->nullable();
            $table->string('model')->nullable();
            $table->string('operation_system')->nullable();
            $table->string('os_version')->nullable();
            $table->string('android_sdk_version')->nullable()->default(null);
            $table->string('platform')->nullable();
            $table->string('manufacture')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('web_version')->nullable();
            $table->string('device_id')->nullable();
            $table->string('device_language')->nullable();
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
