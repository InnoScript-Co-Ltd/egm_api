<?php

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
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
        Schema::create('agents', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('partner_id')->nullable()->default(null);
            $table->snowflakeId('main_agent_id')->nullable()->default(null);
            $table->snowflakeId('reference_id')->nullable()->default(null);
            $table->json('level_one')->nullable()->default(null);
            $table->json('level_two')->nullable()->default(null);
            $table->json('level_three')->nullable()->default(null);
            $table->json('level_four')->nullable()->default(null);
            $table->float('point', 9, 2)->default(0);
            $table->string('profile')->nullable()->default(null);
            $table->string('username')->unique()->nullable()->default(null);
            $table->string('first_name');
            $table->string('last_name');
            $table->date('dob')->nullable()->default(null);
            $table->string('nrc')->nullable()->default(null);
            $table->string('nrc_front')->nullable()->default(null);
            $table->string('nrc_back')->nullable()->default(null);
            $table->string('email')->unique();
            $table->string('prefix')->nullable()->default(null);
            $table->string('phone')->unique();
            $table->snowflakeId('country_id')->nullable()->default(null);
            $table->snowflakeId('region_or_state_id')->nullable()->default(null);
            $table->snowflakeId('city_id')->nullable()->default(null);
            $table->snowflakeId('township_id')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('password')->nullable()->default(null);
            $table->string('payment_password')->nullable()->default(null);
            $table->datetime('email_verified_at')->nullable()->default(null);
            $table->datetime('phone_verified_at')->nullable()->default(null);
            $table->string('kyc_status')->default(KycStatusEnum::CHECKING->value);
            $table->string('status')->default(AgentStatusEnum::PENDING->value);
            $table->string('agent_type')->default(AgentTypeEnum::SUB_AGENT->value);
            $table->string('email_verify_code')->nullable()->default(null);
            $table->datetime('email_expired_at')->nullable()->default(null);
            $table->auditColumns();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('region_or_state_id')->references('id')->on('regions_and_states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('township_id')->references('id')->on('townships')->onDelete('cascade');

            $table->foreign('reference_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('main_agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
