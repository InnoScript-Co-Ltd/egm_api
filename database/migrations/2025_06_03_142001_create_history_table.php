<?php

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
        Schema::create('history', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('partner_id');
            $table->snowflakeId('repayment_id')->nullable(null);
            $table->snowflakeId('withdraw_id')->nullable(null);
            $table->snowflakeId('transaction_id')->nullable(null);
            $table->string('type');
            $table->float('repayment_amount', 12, 2)->nullable()->default(null);
            $table->float('deposit_amount', 12, 2)->nullable()->default(null);
            $table->float('withdraw_amount', 12, 2)->nullable()->default(null);
            $table->string('title');
            $table->text('description');
            $table->string('status');
            $table->auditColumns();

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('repayment_id')->references('id')->on('repayments')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
