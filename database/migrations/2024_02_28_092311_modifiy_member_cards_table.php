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
        Schema::table('membercards', function (Blueprint $table) {
            $table->date('expired_at')->nullable()->change();
            $table->date('front_background')->remove();
            $table->date('back_background')->remove();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membercards', function (Blueprint $table) {
            $table->date('expired_at')->nullable()->change();
            $table->date('front_background')->remove();
            $table->date('back_background')->remove();
        });
    }
};
