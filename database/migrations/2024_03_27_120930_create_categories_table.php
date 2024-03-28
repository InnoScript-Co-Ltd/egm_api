<?php

use App\Enums\GeneralStatusEnum;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('name')->unique();
            $table->string('description')->nullable()->default(null);
            $table->string('app_type');
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
