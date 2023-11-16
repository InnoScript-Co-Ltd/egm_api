<?php

use App\Enums\OrderStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('delivery_address_id');
            $table->snowflakeId('user_id');
            $table->string('user_name');
            $table->string('phone');
            $table->string('email');
            $table->longtext('delivery_address');
            $table->string('delivery_contact_person');
            $table->string('delivery_contact_phone');
            $table->float('discount', 9, 2);
            $table->float('delivery_feed', 9, 2);
            $table->float('total_amount', 9, 2);
            $table->json('items');
            $table->string('payment_type');
            $table->string('status')->default(OrderStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('delivery_address_id')->references('id')->on('delivery_address')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
