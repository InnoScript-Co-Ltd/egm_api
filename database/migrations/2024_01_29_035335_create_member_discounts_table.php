<?php

use App\Enums\MemberDiscountStatus;
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
        Schema::create('member_discounts', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->string('label')->unique();
            $table->string('discount_percentage')->default(null);
            $table->float('discount_fix_amount', 9, 2)->default(0);
            $table->string('expend_limit')->default(null);
            $table->boolean('is_expend_limit')->default(false);
            $table->boolean('is_fix_amount')->default(false);
            $table->datetime('start_date')->nullable()->default(null);
            $table->datetime('end_date')->nullable()->default(null);
            $table->string('status')->default(MemberDiscountStatus::DISABLE->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_discounts');
    }
};
