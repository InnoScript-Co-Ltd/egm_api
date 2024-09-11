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
        Schema::create('comments', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('article_type_id');
            $table->snowflakeId('article_id');
            $table->string('comment')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->json('photos')->nullable();
            $table->longText('content')->nullable();
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();

            $table->foreign('article_type_id')->references('id')->on('article_types')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
