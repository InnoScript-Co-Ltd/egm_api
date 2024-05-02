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
        Schema::create('invoice', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('order_id')->nullable()->default(null);
            $table->string('username');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default(GeneralStatusEnum::DISABLE->value);
            $table->auditColumns();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
