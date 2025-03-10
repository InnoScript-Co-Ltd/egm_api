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
        Schema::create('referrals', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id')->nullable()->default(null);
            $table->snowflakeId('main_agent_id')->nullable()->default(null);
            $table->snowflakeId('partner_id')->nullable()->default(null);
            $table->json('register_agents')->nullable()->default(null);
            $table->string('agent_type')->nullable()->default(null);
            $table->bigInteger('commission')->unsigned()->nullable()->default(null);
            $table->string('link');
            $table->integer('count')->unsigned();
            $table->string('referral_type')->nullable()->default(null);
            $table->date('expired_at')->nullable();
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('main_agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
