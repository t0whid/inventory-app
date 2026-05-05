<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_settings', function (Blueprint $table) {
            $table->id();
            $table->text('bot_token')->nullable();
            $table->string('bot_username')->nullable();
            $table->string('admin_chat_id')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamp('last_chat_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_settings');
    }
};