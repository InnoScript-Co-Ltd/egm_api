<?php

use App\Enums\AgentStatusEnum;
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
        Schema::create('sub_agents', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('roi_rate')->nullable();
            $table->string('nrc_front')->nullable()->default(null);
            $table->string('nrc_back')->nullable()->default(null);
            $table->string('nrc')->nullable()->default(null)->unique();
            $table->string('phone')->nullable()->default(null)->unique();
            $table->string('email')->nullable()->default(null)->unique();
            $table->string('status')->default(AgentStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_agents');
    }
};
