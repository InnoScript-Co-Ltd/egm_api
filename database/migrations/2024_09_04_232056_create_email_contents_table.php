<?php

use App\Enums\EmailContentTypeEnum;
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
        Schema::create('email_contents', function (Blueprint $table) {
            $table->snowflakeIdAndPrimary();
            $table->snowflakeId('country_id');
            $table->string('country_code');
            $table->string('content_type')->default(EmailContentTypeEnum::PARTNER_ACCOUNT_OPENING->value);
            $table->string('title');
            $table->longText('content');
            $table->string('template')->default(null);
            $table->string('status')->default(GeneralStatusEnum::ACTIVE->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_contents');
    }
};
