<?php

use App\Enums\BankAccountLimitEnum;
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
        Schema::table('merchant_bank_accounts', function (Blueprint $table) {
            $table->string('transaction_limit_status')->default(BankAccountLimitEnum::AVAILABLE->value)->after('transaction_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchant_bank_accounts', function (Blueprint $table) {
            $table->drop('transaction_limit_status');
        });
    }
};
