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
            $table->snowflakeId('agent_id');
            $table->snowflakeId('bank_account_id');
            $table->snowflakeId('merchant_account_id');
            $table->snowflakeId('package_id');
            $table->string('agent_name');
            $table->string('agent_email');
            $table->string('agent_phone');
            $table->string('agent_nrc');
            $table->string('agent_address');
            $table->string('agent_account_name');
            $table->string('agent_account_number');
            $table->string('agent_bank_branch');
            $table->string('agent_bank_address');
            $table->string('merchant_account_name');
            $table->string('merchant_account_number');
            $table->string('bank_type');
            $table->string('package_name');
            $table->integer('package_roi_rate')->unsigned();
            $table->integer('package_duration')->unsigned();
            $table->float('package_deposit_amount', 15, 2);
            $table->string('transaction_screenshoot');
            $table->string('transaction_type');
            $table->dateTime('expired_at')->nullable();
            $table->string('status');
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('bank_account_id')->references('id')->on('agent_bank_accounts')->onDelete('cascade');
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
