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
        Schema::create('items_in_promotion', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('item_id');
            $table->snowflakeId('promotion_id');
            $table->float('promotion_price', 9, 2)->default(0);
            $table->string('status')->default('ACTIVE');
            $table->auditColumns();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_in_promotion');
    }
};
