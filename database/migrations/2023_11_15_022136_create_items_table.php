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
        Schema::create('items', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('category_id');
            $table->snowflakeId('shop_id');
            $table->string('name');
            $table->json('images')->nullable()->default(null);
            $table->string('code');
            $table->longtext('description')->nullable()->default(null);
            $table->longtext('content')->nullable()->default(null);
            $table->float('price', 9, 2)->nullable()->default(null);
            $table->float('sell_price', 9, 2);
            $table->boolean('out_of_stock')->default(false);
            $table->integer('instock')->default(false);
            $table->string('status')->default(GeneralStatusEnum::DISABLE->value);
            $table->auditColumns();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
