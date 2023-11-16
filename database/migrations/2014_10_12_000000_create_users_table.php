<?php

use App\Enums\UserStatusEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('name')->unique();
            $table->string('profile')->nullable()->default(null);
            $table->integer('reward_point')->default(0);
            $table->json('coupons')->nullable()->default(null);
            $table->string('phone')->unique()->nullable()->default(null);
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('password');
            $table->json('cart_items')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->timestamp('phone_verified_at')->nullable()->default(null);
            $table->string('status')->default(UserStatusEnum::PENDING->value);
            $table->rememberToken();
            $table->auditColumns();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
