<?php

use App\Enums\InvestorStatusEnum;
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
        Schema::create('investors', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id')->nullable()->default(null);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->unique();
            $table->string('mobile_prefix');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->date('dob')->nullable()->default(null);
            $table->string('nrc')->nullable()->unique();
            $table->string('nrc_front')->nullable()->default(null);
            $table->string('nrc_back')->nullable()->default(null);
            $table->datetime('email_verified_at')->nullable()->default(null);
            $table->datetime('phone_verified_at')->nullable()->default(null);
            $table->snowflakeId('country_id')->nullable()->default(null);
            $table->snowflakeId('region_or_state_id')->nullable()->default(null);
            $table->snowflakeId('city_id')->nullable()->default(null);
            $table->snowflakeId('township_id')->nullable()->default(null);
            $table->string('kyc_status')->default(KycStatusEnum::NONE->value);
            $table->string('status')->default(InvestorStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('region_or_state_id')->references('id')->on('regions_and_states')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('township_id')->references('id')->on('townships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
