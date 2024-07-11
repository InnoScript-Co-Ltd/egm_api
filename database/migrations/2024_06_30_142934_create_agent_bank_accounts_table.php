<?php

use App\Enums\BankAccountStatusEnum;
use App\Enums\DefaultStatusEnum;
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
        Schema::create('agent_bank_accounts', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('agent_id');
            $table->string('account_name');
            $table->string('account_number')->unique();
            $table->string("address")->nullable()->default(null);
            $table->string("branch")->nullable()->default(null);
            $table->string("default_account")->default(DefaultStatusEnum::NONE->value);
            $table->string("status")->default(BankAccountStatusEnum::ACTIVE->value);
            $table->auditColumns();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_bank_accounts');
    }
};
