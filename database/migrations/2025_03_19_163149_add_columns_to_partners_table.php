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
        Schema::table('partners', function (Blueprint $table) {
            $table->float('total_deposit', 12, 2)->nullable()->default(0)->after('referral');
            $table->float('monthly_repayment_amount', 12, 2)->nullable()->default(0)->after('referral');
            $table->float('total_commission', 12, 2)->nullable()->default(0)->after('referral');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('total_deposit');
            $table->dropColumn('monthly_repayment_amount');
            $table->dropColumn('total_commission');
        });
    }
};
