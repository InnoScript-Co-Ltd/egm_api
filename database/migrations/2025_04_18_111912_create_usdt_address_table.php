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
        Schema::create('usdt_address', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('partner_id');
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('address_type')->default('MERCHANT');
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usdt_address');
    }
};
