<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\GeneralStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('title');
            $table->integer('level')->default(0);
            $table->snowflakeId('category_id')->nullable()->default(null);
            $table->longtext('description')->nullable()->default(null);
            $table->string('status')->default(GeneralStatusEnum::DISABLE->value);
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
