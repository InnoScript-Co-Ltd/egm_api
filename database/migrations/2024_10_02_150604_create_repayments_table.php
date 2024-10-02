<?php

use App\Enums\RepaymentStatusEnum;
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
        Schema::create('repayments', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('deposit_id');
            $table->snowflakeId('transaction_id');
            $table->date('date');
            $table->float('amount', 9, 2);
            $table->float('total_amount', 9, 2);
            $table->float('oneday_amount', 9, 2);
            $table->bigInteger('count_days')->unsigned();
            $table->bigInteger('total_days')->unsigned();
            $table->string('status')->default(RepaymentStatusEnum::AVAILABLE_WITHDRAW->value);
            $table->auditColumns();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('deposit_id')->references('id')->on('deposits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
