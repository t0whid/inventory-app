<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('out_of_stocks', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();

            $table->time('marked_time')->nullable();

            $table->timestamps();

            $table->unique(['date', 'product_id', 'staff_id'], 'unique_daily_oos_staff_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('out_of_stocks');
    }
};