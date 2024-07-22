<?php

use App\Enums\PackageBuyStatusEnum;
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
        Schema::create('investor_packages', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('investor_id');
            $table->snowflakeId('agent_id')->nullable()->default(null);
            $table->snowflakeId('package_id');
            $table->string('package_name');
            $table->string('package_roi_rate');
            $table->integer('package_duration');
            $table->string('package_deposit_rate');
            $table->string('agent_name');
            $table->string('agent_phone');
            $table->string('agent_email');
            $table->float('exchange_rate', 9, 2)->nullable()->default(null);
            $table->float('depost_amount', 9, 2)->nullable()->default(null);
            $table->dateTime('package_start_at')->nullable()->default(null);
            $table->dateTime('package_expired_at')->nullable()->default(null);
            $table->string('status')->default(PackageBuyStatusEnum::REQUEST->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investor_packages');
    }
};
