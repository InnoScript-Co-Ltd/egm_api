<?php

use App\Enums\AppTypeEnum;
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
        Schema::create('shops', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('country_id');
            $table->snowflakeId('region_or_state_id');
            $table->snowflakeId('city_id');
            $table->snowflakeId('township_id');
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique()->nullable()->default(null);
            $table->string('description')->nullable()->default(null);
            $table->string('address');
            $table->string('app_type')->default(AppTypeEnum::GSCEXPORT->value);
            $table->longtext('location')->nullable()->default(null);
            $table->string('status')->default(GeneralStatusEnum::DISABLE->value);
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
        Schema::dropIfExists('shops');
    }
};
