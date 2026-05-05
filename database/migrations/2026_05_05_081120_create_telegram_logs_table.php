<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('message');
            $table->string('chat_id')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->json('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('chat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_logs');
    }
};