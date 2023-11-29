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
        Schema::create('shops', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('region_id');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->longtext('location')->nullable()->default(null);
            $table->string('status')->default(GeneralStatusEnum::DISABLE->value);
            $table->auditColumns();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
