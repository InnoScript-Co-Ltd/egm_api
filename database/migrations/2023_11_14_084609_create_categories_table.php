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
            $table->string('title')->unique();
            $table->snowflakeId('icon')->nullable()->default(null);
            $table->integer('level')->default(0);
            $table->snowflakeId('main_category_id')->nullable()->default(null);
            $table->longtext('description')->nullable()->default(null);
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
