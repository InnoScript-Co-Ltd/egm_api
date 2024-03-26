<?php

use App\Enums\GeneralStatusEnum;
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
        Schema::create('regions_and_states', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('country_id');
            $table->string('name')->unique();
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions_and_states');
    }
};
