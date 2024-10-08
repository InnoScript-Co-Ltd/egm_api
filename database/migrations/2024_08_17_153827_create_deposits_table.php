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
        Schema::create('deposits', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id')->nullable()->default(null);
            $table->snowflakeId('partner_id')->nullable()->default(null);
            $table->snowflakeId('transaction_id');
            $table->float('deposit_amount', 12, 2)->default(0);
            $table->float('roi_amount', 12, 2)->default(0);
            $table->float('commission_amount', 12, 2)->default(0);
            $table->dateTime('expired_at')->nullable();
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
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
