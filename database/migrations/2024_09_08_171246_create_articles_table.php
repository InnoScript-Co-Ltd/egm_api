<?php

use App\Enums\ArticleStatusEnum;
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
            $table->longText('description')->nullable();
            $table->json('photos');
            $table->longText('content')->nullable();
            $table->string('status')->default(ArticleStatusEnum::PUBLISHED->value);
            $table->boolean('is_popular_news')->default(false);
            $table->boolean('is_latest_news')->default(false);
            $table->boolean('is_breaking_news')->default(false);
            $table->auditColumns();

            $table->foreign('article_type_id')->references('id')->on('article_types')->onDelete('cascade');
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
