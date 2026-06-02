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
        Schema::create('inventory_count_items', function (Blueprint $table) {
            $table->id();

            // Link to the parent inventory count
            $table->foreignId('inventory_count_id')
                ->constrained()
                ->onDelete('cascade'); // If count deleted, remove its items

            // The product being counted
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if referenced in counts

            // Optional link to the barcode used during counting
            $table->foreignId('barcode_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // Quantities
            $table->integer('system_quantity');   // What the system thinks is in stock
            $table->integer('counted_quantity');  // What was physically counted
            $table->integer('difference');        // counted_quantity - system_quantity

            // Status tracking
            $table->enum('status', [
                'pending',   // Not yet counted
                'counted',   // Scanned/entered but not verified
                'verified',  // Verified by supervisor
                'adjusted'   // Stock has been adjusted based on this count
            ])->default('pending');

            // Users involved
            $table->foreignId('counted_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Additional info
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Extra data (e.g., scan timestamp, device ID)

            $table->timestamps();

            // Indexes for performance
            $table->index('inventory_count_id');
            $table->index('product_item_id');
            $table->index('status');
            $table->index('barcode_id');

            // Unique constraint: one product per count
            $table->unique(['inventory_count_id', 'product_item_id'], 'count_product_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_count_items');
    }
};
