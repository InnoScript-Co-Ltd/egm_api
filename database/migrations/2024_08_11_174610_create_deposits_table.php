<?php

use App\Enums\OrderStatusEnum;
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
        Schema::create('deposits', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id');
            $table->string('agent_name');
            $table->string('agent_email');
            $table->string('agent_phone');
            $table->string('agent_nrc');
            $table->string('agent_address');
            $table->snowflakeId('package_id');
            $table->string('name');
            $table->integer('roi_rate')->unsigned();
            $table->integer('duration')->unsigned();
            $table->float('deposit_amount', 30, 2);
            $table->snowflakeId('bank_account_id');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('bank_type');
            $table->string('branch');
            $table->string('branch_address');
            $table->string('merchant_account');
            $table->string('transaction_screenshoot');
            $table->string('status')->default(OrderStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('bank_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
