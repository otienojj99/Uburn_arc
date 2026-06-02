<?php

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
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('cascade'); // If product deleted, remove pivot entry

            $table->foreignId('discount_id')
                ->constrained()
                ->onDelete('cascade'); // If discount deleted, remove pivot entry

            $table->timestamps();

            // Ensure a product can't be linked to the same discount twice
            $table->unique(['product_item_id', 'discount_id'], 'product_discount_unique');

            // Indexes for faster queries
            $table->index('product_item_id');
            $table->index('discount_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_discounts');
    }
};
