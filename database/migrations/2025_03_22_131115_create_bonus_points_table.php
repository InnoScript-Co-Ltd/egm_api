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
        Schema::create('bonus_points', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string("label")->unique();
            $table->float('limit_amount', 12,2)->default(0);
            $table->string("status")->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_points');
    }
};
