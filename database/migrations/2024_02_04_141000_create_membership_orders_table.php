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
        Schema::create('membership_orders', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('member_id');
            $table->snowflakeId('user_id');
            $table->string('order_number');
            $table->string('card_type');
            $table->string('card_number');
            $table->string('name');
            $table->string('phone')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->float('amount', 9, 2);
            $table->float('discount', 9, 2)->default(0);
            $table->float('total', 9, 2);
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_orders');
    }
};
