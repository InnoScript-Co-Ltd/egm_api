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
        Schema::create('articles', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('article_type_id');
            $table->string('language')->default('EN');
            $table->string('title')->unique();
            $table->longText('description');
            $table->json('photos');
            $table->longText('content');
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
