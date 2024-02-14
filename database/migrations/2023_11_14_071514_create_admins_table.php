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
        Schema::create('admins', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('name');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->snowflakeId('profile')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable()->default(null);
            $table->timestamp('phone_verified_at')->nullable()->default(null);
            $table->string('status')->default(UserStatusEnum::PENDING->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
