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
        Schema::create('agent_channels', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id');
            $table->string('name');
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_channels');
    }
};
