<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_stocks', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            $table->foreignId('staff_id')
                ->nullable()
                ->constrained('staff')
                ->nullOnDelete();

            $table->integer('opening_stock')->default(0);
            $table->integer('production_qty')->default(0);
            $table->integer('sales_qty')->default(0);
            $table->integer('wastage_qty')->default(0);
            $table->integer('closing_stock')->default(0);

            $table->timestamps();

            $table->unique(['date', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stocks');
    }
};