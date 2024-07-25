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
        Schema::create('agent_in_channels', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('main_agent_id');
            $table->snowflakeId('agent_id');
            $table->snowflakeId('channel_id');
            $table->float('percentage', 9, 2);
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('channel_id')->references('id')->on('agent_channels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_in_channels');
    }
};
