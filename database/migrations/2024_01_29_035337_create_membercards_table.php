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
        Schema::create('membercards', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('label')->unique();
            $table->snowflakeId('discount_id')->nullable()->default(null);
            $table->string('front_background')->nullable()->default(null);
            $table->string('back_background')->nullable()->default(null);
            $table->datetime('expired_at')->nullable()->default(null);
            $table->string('status')->default(GeneralStatusEnum::PENDING->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membercards');
    }
};
