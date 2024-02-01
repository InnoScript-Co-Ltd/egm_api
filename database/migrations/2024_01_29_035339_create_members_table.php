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
        Schema::create('members', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('user_id')->nullable();
            $table->snowflakeId('membercard_id')->nullable()->default(null);
            $table->string('member_id')->unique();
            $table->float('amount', 9, 2)->default(0);
            $table->datetime('expired_at')->nullable();
            $table->string('status')->default(GeneralStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('membercard_id')->references('id')->on('membercards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
