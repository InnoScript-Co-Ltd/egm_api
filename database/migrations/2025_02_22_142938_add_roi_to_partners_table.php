<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            if (! Schema::hasColumn('partners', 'roi')) {
                $table->unsignedBigInteger('roi')->default(16);
            }
        });
    }

    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('roi');
        });
    }
};
