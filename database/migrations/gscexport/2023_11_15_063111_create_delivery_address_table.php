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
        Schema::create('delivery_address', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('user_id');
            $table->longtext('address');
            $table->string('contact_phone');
            $table->string('contact_person');
            $table->boolean('is_default')->default(false);
            $table->auditColumns();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_address');
    }
};
