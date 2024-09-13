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
        Schema::create('partner_bank_accounts', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('partner_id');
            $table->string('account_name');
            $table->string('account_number')->unique();
            $table->string('bank_type');
            $table->string('bank_type_label');
            $table->string('branch')->nullable()->default(null);
            $table->string('branch_address')->nullable()->default(null);
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();

            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_bank_accounts');
    }
};
