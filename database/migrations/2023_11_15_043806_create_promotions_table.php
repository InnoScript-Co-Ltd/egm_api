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
        Schema::create('promotions', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('title');
            $table->string('app_type');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default(GeneralStatusEnum::PENDING->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
