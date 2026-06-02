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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();

            // Core relationships
            $table->foreignId('product_item_id')
                ->constrained()
                ->onDelete('restrict'); // Prevent deletion if transactions exist

            $table->foreignId('warehouse_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            $table->foreignId('user_id') // Who performed the action
                ->nullable()
                ->constrained()
                ->onDelete('set null');

            // Stock movement data
            $table->integer('quantity_change'); // Positive = stock in, Negative = stock out
            $table->integer('stock_before');
            $table->integer('stock_after');

            // Transaction type
            $table->enum('type', [
                'purchase',      // Stock received from supplier
                'sale',          // Stock sold to customer
                'return_in',     // Customer return (stock back in)
                'return_out',    // Return to supplier (stock out)
                'adjustment',    // Manual count adjustment
                'transfer',      // Between warehouses
                'damaged',       // Damaged goods
                'scrap',         // Expired/obsolete
                'initial'        // Initial stock setup
            ]);

            // Polymorphic reference (e.g., order, purchase order)
            $table->string('reference_type')->nullable(); // Model class name
            $table->unsignedBigInteger('reference_id')->nullable();

            // Optional batch/lot tracking
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();

            // Additional info
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Extra data (e.g., scan device info)

            $table->timestamps();

            // ========== INDEXES ==========
            // Critical for fast lookups during reporting
            $table->index('product_item_id');
            $table->index('warehouse_id');
            $table->index('user_id');
            $table->index('type');
            $table->index('created_at');
            $table->index('batch_number');

            // Composite indexes for common queries
            $table->index(['product_item_id', 'created_at']); // Product history
            $table->index(['warehouse_id', 'created_at']);    // Warehouse movements
            $table->index(['reference_type', 'reference_id']); // Find by source document

            // For low stock / reorder queries
            $table->index(['product_item_id', 'type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
