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
        Schema::create('mpe_items', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('category_id');
            $table->snowflakeId('unit_id');
            $table->unsignedBigInteger('unit');
            $table->string('name');
            $table->float('sell_price', 9, 2);
            $table->float('discount_price', 9, 2);
            $table->boolean('is_discount');
            $table->boolean('is_promotion');
            $table->string('status')->default(GeneralStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('category_id')->references('id')->on('mpe_categories')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('mpe_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpe_items');
    }
};
