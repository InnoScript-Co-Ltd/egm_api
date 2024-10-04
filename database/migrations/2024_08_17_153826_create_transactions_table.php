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
        Schema::create('transactions', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('sender_id');
            $table->snowflakeId('sender_account_id');
            $table->snowflakeId('merchant_account_id');
            $table->snowflakeId('package_id')->nullable(null);
            $table->string('sender_name');
            $table->string('sender_email');
            $table->string('sender_phone');
            $table->string('sender_nrc');
            $table->string('sender_address')->nullable();
            $table->string('sender_account_name')->nullable();
            $table->string('sender_account_number');
            $table->string('sender_bank_branch');
            $table->string('sender_bank_address');
            $table->string('merchant_account_name');
            $table->string('merchant_account_number');
            $table->string('bank_type');
            $table->string('package_name');
            $table->integer('package_roi_rate')->unsigned();
            $table->integer('package_duration')->unsigned();
            $table->float('package_deposit_amount', 15, 2);
            $table->string('transaction_screenshoot');
            $table->string('transaction_type');
            $table->string('sender_type');
            $table->string('status');
            $table->auditColumns();

            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('merchant_account_id')->references('id')->on('merchant_bank_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
