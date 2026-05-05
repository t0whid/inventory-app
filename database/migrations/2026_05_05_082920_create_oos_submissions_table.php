<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oos_submissions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->time('submitted_time')->nullable();
            $table->unsignedInteger('oos_count')->default(0);
            $table->timestamps();

            $table->unique(['date', 'staff_id']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oos_submissions');
    }
};