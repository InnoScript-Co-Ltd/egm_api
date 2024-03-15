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
        Schema::create('mpe_users', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('name');
            $table->string('phone')->unique()->nullable()->default(null);
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('gender');
            $table->date('dob')->nullable()->default(null);
            $table->string('occupation')->nullable()->default(null);
            $table->string('position')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->json('cart_items')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->timestamp('phone_verified_at')->nullable()->default(null);
            $table->boolean('is_facebook')->nullable()->default(null);
            $table->boolean('is_google')->nullable()->default(null);
            $table->longText('social_token')->nullable()->default(null);
            $table->string('email_verify_code')->nullable()->default(null);
            $table->dateTime('email_expired_at')->nullable()->default(null);
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
        Schema::dropIfExists('mpe_users');
    }
};
